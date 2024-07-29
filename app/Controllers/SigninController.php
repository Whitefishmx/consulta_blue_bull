<?php
	
	namespace App\Controllers;
	
	use App\Models\UserModel;
	use CodeIgniter\HTTP\RedirectResponse;
	use CodeIgniter\HTTP\ResponseInterface;
	
	class SigninController extends BaseController {
		public function index (): string|RedirectResponse {
			if ( $this->validateSession () ) {
				return redirect ( '/' );
			}
			$data = [ 'session' => FALSE ];
			$data = [ 'main' => view ('signin') ];
			return view ( 'plantilla', $data );
		}
		public function signIn (): ResponseInterface|bool {
			if ( $data = $this->verifyRules ( 'POST', $this->request, NULL ) ) {
				return ( $data );
			}
			$input = $this->getRequestInput ( $this->request );
			$user = new UserModel();
			helper ( 'crypt_helper' );
			$res = $user->validateAccess ( $input[ 'email' ], utf8_encode ( passwordEncrypt ( $input[ 'password' ] ) ), $this->env );
			if ( !$res[ 0 ] ) {
				return $this->errDataSuplied ( 'Las credenciales ingresadas son incorrectas' );
			}
			$session = session ();
			$session->set ( 'logged_in', TRUE );
			$session->set ( 'user', $res[ 1 ] );
			return $this->getResponse ( [ 'error' => 0, 'description' => 'Datos de petición correcto', 'reason' => 'Inicio de sesión exitoso' ] );
		}
	}
