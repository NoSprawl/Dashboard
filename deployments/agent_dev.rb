require 'stringio'
require 'pty'
require 'net/http'
require 'socket'
require 'json'
require 'open-uri'
require 'tmpdir'
require 'socket'
require 'fileutils'

if $stdout.isatty
  if !Process.uid.zero?
    puts "NoSprawl needs root permission to create a crontab ('0 1,13 * * *') and to move the agent script to /usr/local/sbin or /usr/local/bin. Once the agent crontab is running, this environment will report basic information to NoSprawl."
    puts "Permissions failure. Re-run with sudo."
    abort
  end
  
  File.open("/usr/local/sbin/nosprawl.rb", 'w') do |cronjob_script|
    if(system "curl --version")
      version = `curl http://nos.agent.s3-website-us-east-1.amazonaws.com/latest`
      curl_res = `curl http://nos.agent.s3-website-us-east-1.amazonaws.com/#{version}`
    else
      version = `wget -q -O - "$@" http://nos.agent.s3-website-us-east-1.amazonaws.com/latest`
      curl_res = `wget -q -O - "$@" http://nos.agent.s3-website-us-east-1.amazonaws.com/#{version}`
    end
    
    cronjob_script.write curl_res
  end
  
  ruby_loc = `which ruby`.strip
  `echo '0 1,13 * * * #{ruby_loc} /usr/local/sbin/nosprawl.rb' | sudo crontab`
end

class Selfie
  class << self
    def from base_url
      @@base_url = base_url
      output = open(URI.parse "#{base_url}/latest")
      @@latest_version = output.readlines.join('').to_i

      if version_mismatch
        get_latest
        apply_latest
        exit
      end

    end

    def get_latest
      output = open(URI.parse "#{@@base_url}/#{@@latest_version}")
      @@latest_code = output.readlines.join ''
    end

    def apply_latest
      File.open(__FILE__, 'w') { |me| me.write(@@latest_code) }
    end

    def version_mismatch
      return VERSION != @@latest_version
    end

  end

end

module NoSprawlReportingAgent
  Selfie.from 'http://agent.nosprawl.software'
  
  class NoSprawlPackageManagerAbstraction
    def initialize
      detect_package_manager
    end
    
    def detect_package_manager
      disable_output
      ['apt-get', 'yum', 'brew'].each do |package_manager|
        if system("which #{package_manager} > /dev/null 2>&1")
          enable_output
          @package_manager = package_manager
          return package_manager
        end
        
      end
      
      enable_output
    end
    
    def disable_output
      @out ||= $stdout.clone
      $stdout.reopen File.new('/dev/null', 'w')
    end
    
    def enable_output
      $stdout.reopen @out
    end
    
    def versions
      implementations = {'yum' => YumParse, 'apt-get' => AptParse}
      implementations[@package_manager].versions @package_manager
    end
    
  end
  
  class YumParse
    class << self
      def versions package_manager
        outtmp = ''
        begin
          PTY.spawn("su - -c 'yum history' | grep Update") do |r, w, pid|
            begin
              r.each { |line| outtmp << line}
            rescue
              
            end
            
          end
          
        end
                
        hash = {:installed => `yum list installed`.scan(/^([\S]+)\.[\S]+[\s]+([\S|\.]+)/),
                :package_manager => package_manager,
                :last_updated => (Time.at(/\| (\d.+) \| Update/.match(outtmp)[1]).to_i rescue "Never"),
                :platform => `uname`.strip}
        hash
      end
      
    end
    
  end
  
  class AptParse
    class << self
      def versions package_manager
        outtmp = ''
        
        begin
          PTY.spawn("stat -c %Y /var/cache/apt/") do |r, w, pid|
            begin
              r.each { |line| outtmp << line}
            rescue
              
            end
            
          end
          
        end
                
        hash = {:installed => `sudo dpkg -l`.scan(/ii\s+([\w+|[0-9]|\:|\+|\.|[\-]*]+)[\s]+([0-9|\.|\:W]+)/),
                :package_manager => package_manager,
                :last_updated => (Time.at(outtmp).to_i rescue "Never"),
                :platform => `uname`.strip}
        
        hash
        
      end
      
    end
    
  end
  
  pkgman = NoSprawlPackageManagerAbstraction.new
  
  virtual = ((`dmesg |grep -i hypervisor` != "") rescue true)
  
  structure = {:job => 'ProcessAgentReport',
               :data => {:message => 
                        {:ips => (`hostname -I`.strip.split(" ") rescue []),
                         :hostname => Socket.gethostname,
                         :pkginfo => pkgman.versions,
                         :virtual => virtual}}}
                         
  uri = URI.parse "https://sqs.us-east-1.amazonaws.com/373233922238/nosprawl-sqs-va"
  response = Net::HTTP.post_form uri, {:Action => 'SendMessage', :Version => '2011-10-01', :MessageBody => "#{structure.to_json}"}
end
