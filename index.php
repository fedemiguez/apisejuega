<?php


ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require 'vendor/autoload.php';
require 'Models/User.php';


$app = new \Slim\Slim();

$app->config('databases', [
    'default' => [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'api-prog4',
        'username'  => 'sejuega',
        'password'  => 'sejuega',
        'charset'   => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix'    => ''
    ]
]);

$app->add(new Zeuxisoo\Laravel\Database\Eloquent\ModelMiddleware);
$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());
$app->add(new \Slim\Middleware\ContentTypes());

$app->options('/(:name+)', function() use ($app) {
    $app->render(200,array('msg' => 'API SeJuega'));
});

$app->get('/', function () use ($app) {
	$app->render(200,array('msg' => 'API SeJuega'));
});
$app->get('/usuario', function () use ($app) {
	$db = $app->db->getConnection();
	$users = $db->table('users')->select('id', 'name')->get();
	$app->render(200,array('data' => $users));
});
$app->post('/usuario', function () use ($app) {
	$input = $app->request->getBody();
	$name = $input['name'];
	if(empty($name)){
		$app->render(500,array(
			'error' => TRUE,
            'msg'   => 'name is required',
        ));
	}
	$password = $input['password'];
	if(empty($password)){
		$app->render(500,array(
			'error' => TRUE,
            'msg'   => 'password is required',
        ));
	}
	$email = $input['email'];
	if(empty($email)){
		$app->render(500,array(
			'error' => TRUE,
            'msg'   => 'email is required',
        ));
	}
    $user = new User();
    $user->name = $name;
    $user->password = $password;
    $user->email = $email;
    $user->save();
    $app->render(200,array('data' => $user->toArray()));
});
$app->put('/usuario/:id', function ($id) use ($app) {
	$input = $app->request->getBody();
	
	$name = $input['name'];
	if(empty($name)){
		$app->render(500,array(
			'error' => TRUE,
            'msg'   => 'name is required',
        ));
	}
	$password = $input['password'];
	if(empty($password)){
		$app->render(500,array(
			'error' => TRUE,
            'msg'   => 'password is required',
        ));
	}
	$email = $input['email'];
	if(empty($email)){
		$app->render(500,array(
			'error' => TRUE,
            'msg'   => 'email is required',
        ));
	}
	$user = User::find($id);
	if(empty($user)){
		$app->render(404,array(
			'error' => TRUE,
            'msg'   => 'user not found',
        ));
	}
    $user->name = $name;
    $user->password = $password;
    $user->email = $email;
    $user->save();
    $app->render(200,array('data' => $user->toArray()));
});
$app->get('/usuario/:id', function ($id) use ($app) {
	$user = User::find($id);
	if(empty($user)){
		$app->render(404,array(
			'error' => TRUE,
            'msg'   => 'user not found',
        ));
	}
	$app->render(200,array('data' => $user->toArray()));
});
$app->delete('/usuario/:id', function ($id) use ($app) {
	$user = User::find($id);
	if(empty($user)){
		$app->render(404,array(
			'error' => TRUE,
            'msg'   => 'user not found',
        ));
	}
	$user->delete();
	$app->render(200);
});

$app->get('/partidos', function () use ($app) {
	$db = $app->db->getConnection();
	$users = $db->table('partidos')->select('id', 'nombre', 'fecha')->get();

	$app->render(200,array('data' => $users));
});

$app->post('/partidos', function () use ($app) {


    $user = new Partido();
    $user->nombre = $app->request->params('nombre');
    $user->fecha = $app->request->params('fecha');
    $user->participantes = $app->request->params('participantes');
    $user->id_usuario = $app->request->params('id_usuario');
    $user->save();

    $app->render(200,array('data' => $user->toArray()));
});

$app->put('/partido/:id', function ($id) use ($app) {
	$name = $app->request->params('nombre');
	if(empty($name)){
		$app->render(500,array(
			'error' => TRUE,
            'msg'   => 'name is required',
        ));
	}
	$password = $app->request->params('fecha');
	if(empty($password)){
		$app->render(500,array(
			'error' => TRUE,
            'msg'   => 'password is required',
        ));
	}
	$email = $app->request->params('participantes');
	if(empty($email)){
		$app->render(500,array(
			'error' => TRUE,
            'msg'   => 'email is required',
        ));
	}

	$user = Partido::find($id);
	if(empty($user)){
		$app->render(404,array(
			'error' => TRUE,
            'msg'   => 'user not found',
        ));
	}
  $user->nombre = $app->request->params('nombre');
  $user->fecha = $app->request->params('fecha');
  $user->participantes = $app->request->params('participantes');
  $user->id_usuario = $app->request->params('id_usuario');
    $user->save();
    $app->render(200,array('data' => $user->toArray()));
});

$app->get('/partido/:id', function ($id) use ($app) {
	$user = Partido::find($id);
	if(empty($user)){
		$app->render(404,array(
			'error' => TRUE,
            'msg'   => 'user not found',
        ));
	}
	$app->render(200,array('data' => $user->toArray()));
});

$app->delete('/partido/:id', function ($id) use ($app) {
	$user = Partido::find($id);
	if(empty($user)){
		$app->render(404,array(
			'error' => TRUE,
            'msg'   => 'user not found',
        ));
	}

	$user->delete();
	$app->render(200);
});





$app->run();
?>
