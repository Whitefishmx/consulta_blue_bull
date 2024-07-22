<?php
	
	namespace App\Controllers;
	
	use App\Models\BlueBullModel;
	use CodeIgniter\HTTP\RedirectResponse;
	use CodeIgniter\HTTP\ResponseInterface;
	
	class BlueBullController extends BaseController {
		public function index () {
			if ( $this->validateSession () ) {
				return view ( 'header' ) . view ( 'bluebull' ) . view ( 'footer' );
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
			if ( $_FILES[ 'letter' ][ 'error' ] !== UPLOAD_ERR_OK ) {
				return $this->errDataSuplied ( 'No se logró cargar el archivo' );
			}
			$uploadedFile = $_FILES[ 'letter' ];
			$base64 = base64_encode ( file_get_contents ( $uploadedFile[ 'tmp_name' ] ) );
			$data = [
				'rfc' => $input[ 'rfc' ],
				'base64' => $base64,
				'type' => explode ( '/', $uploadedFile[ 'type' ] )[ 1 ],
			];
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