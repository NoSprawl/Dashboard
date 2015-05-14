<?php

class NodesController extends \BaseController {

	public function __construct()
    {
        
        $this->beforeFilter('csrf', ['only' => ['store', 'update']] );
        $this->beforeFilter('isNodeOwner', ['only' => ['edit', 'update', 'destroy']] );

    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$nodes = Auth::user()->nodes;
		Return View::make('nodes.main')->with('nodes', $nodes);
	}
	
	public function placeNodeInLimbo($node_id, $integration_id) {
		$node = Node::find($node_id);
		$node->limbo = true;
		Queue::push('DeployAgentToNode', array('message' => array('node_id' => $node_id, 'integration_id' => $integration_id)));
		$node->save();
	}


	public function listPackages($node_id) {
		$packages = Node::find($node_id)->packages()->get();
		$items_for_vulnerability_query = array();
		return Response::json(array('packages' => $packages));
	}
	
	public function getVulnerabilityInfoFor() {
		$input = Input::only('product', 'upstream_version');
		$mongo_client = new MongoClient('mongodb://php_worker3:shadowwood@linus.mongohq.com:10026/nosprawl_vulnerabilities');
		$mongo_database = $mongo_client->selectDB('nosprawl_vulnerabilities');
		$mongo_collection = new MongoCollection($mongo_database, 'vulnerabilities');
		$vulnerability_doc = $mongo_collection->findOne(array('product' => $input['product'], 'version' => $input['upstream_version']));
		if($vulnerability_doc) {
			return Response::json($vulnerability_doc);
		}
		
		return Response::json();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		Return View::make('nodes.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::only('name', 'description');

		$rules = [
					'name' => 	'required|max:64',
					'description' => 'max:200',
				 ];

		$validator = Validator::make($input, $rules);

		if($validator->passes()) 
		{

			$node = new Node;
			$node->name = $input['name'];
			$node->description = $input['description'];
			$node->owner_id = Auth::user()->id;
			$node->save();

			return Redirect::back()->with('message', 'Node ' . $node->name . ' created');

		}

		return Redirect::back()->withErrors($validator);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$node = Node::find($id);

		return View::make('nodes.edit')->with('node', $node);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::only('name', 'description');

		$rules = [
					'name' => 	'required|max:64',
					'description' => 'max:200',
				 ];

		$validator = Validator::make($input, $rules);

		if($validator->passes()) 
		{

			$node = Node::find($id);
			$node->name = $input['name'];
			$node->description = $input['description'];
			$node->save();
			return Redirect::to('nodes')->with('message', 'Node ' . $node->name . ' updated');

		}

		return Redirect::back()->withErrors($validator);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		
		$node = Node::find($id);
		$message = 'Node ' . $node->name . ' has been deleted';

		$node->delete();

		return Redirect::to('nodes')->with('message', $message);

		
	}


}
