<?php
	
	namespace App\Controllers;
	
	use App\Models\BlueBullModel;
	use CodeIgniter\HTTP\RedirectResponse;
	use CodeIgniter\HTTP\ResponseInterface;
	
	class BlueBullController extends BaseController {
		public function index () {
			if ( $this->validateSession () ) {
				$data = [ 'main' => view ('bluebull') ];
				return view ( 'plantilla', $data );
			}
			return redirect()->route('signin');
		}
		/**
		 * Permite obtener el límite de crédito de un RFC ingresado
		 * @return ResponseInterface|bool
		 */
		public function searchRfc (): ResponseInterface|bool {
			if ( $data = $this->verifyRules ( 'POST', $this->request, NULL ) ) {
				return ( $data );
			}
			$input = $this->getRequestInput ( $this->request );
			$data = [];
			if ( !isset($_FILES[ 'letter' ])) {
				$data = [
					'rfc' => $input[ 'rfc' ],
					'base64' => '',
					'type' => 'jpeg',
				];
			}else if ($_FILES[ 'letter' ][ 'error' ] == UPLOAD_ERR_OK){
				$uploadedFile = $_FILES[ 'letter' ];
				$base64 = base64_encode ( file_get_contents ( $uploadedFile[ 'tmp_name' ] ) );
				$data = [
					'rfc' => $input[ 'rfc' ],
					'base64' => $base64,
					'type' => explode ( '/', $uploadedFile[ 'type' ] )[ 1 ],
				];
			}
			$bBull = new BlueBullModel();
			$fichas = $bBull->consultaFichas ( $data, $this->env );
			if ( !$fichas[ 0 ] ) {
				return $this->serverError ( 'Error con el RFC ingresado', $fichas[ 1 ] );
			}
			$limite = array_map ( function ( $value ) use ( $bBull ) {
				return $bBull->consultaLimite ( $value, $this->env );
			}, $fichas );
			if ( count ( $limite ) === 1 && !$limite[ 0 ] ) {
				return $this->serverError ( 'Error con el RFC ingresado.', $limite[ 1 ] );
			}
			return $this->getResponse ( $limite );
		}
	}