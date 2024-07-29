<?php
	
	namespace App\Models;
	class BlueBullModel extends BaseModel {
		private string $sandboxUrl = 'https://sad.bluebull.mx/demob/integrador_wsdl.php';
		private string $liveUrl = 'https://sad.bluebull.mx/gobsonora/integrador_wsdl.php';
		/**
		 * Consulta la ficha de un rfc
		 *
		 * @param array       $args [rfc, cartaBase64, extensionImagen]
		 * @param string|NULL $env  Ambiente en el que se va a trabajar
		 *
		 * @return bool|array Devuelve los fichas para consulta de límite
		 */
		public function consultaFichas ( array $args, string $env = NULL ): bool|array {
			$this->environment = $env === NULL ? $this->environment : $env;
			$url = strtoupper ( $this->environment ) === 'SANDBOX' ? $this->sandboxUrl : $this->liveUrl;
			$endpoint = 'CONSULTAFICHAS';
			$data =
				"<rfc>{$args['rfc']}</rfc>
                <documento_autorizacion_datos>
                <![CDATA[data:image/{$args['type']};base64,{$args['base64']}]]>
                </documento_autorizacion_datos>";
			$res = $this->sendRequest ( $url, $data, $endpoint );
			libxml_use_internal_errors ( TRUE );
			$xml = simplexml_load_string ( $res );
			if ( $xml === FALSE ) {
				return [ FALSE, 'Error al consultar Blue Bull' ];
			}
			if ( isset( $xml->transaccion->error ) ) {
				return [ FALSE, (string)$xml->transaccion->error->descripcion ];
			}
			$links = [];
			foreach ( $xml->transaccion->vinculo as $vinculo ) {
				$links[] = [
					'ficha' => (string)$vinculo->ficha,
					'rfc' => (string)$vinculo->rfc,
					'nomina' => (string)$vinculo->nomina,
					'clave' => (string)$vinculo->clave,
					'nombre' => (string)$vinculo->nombre,
					'tipo_limite' => (string)$vinculo->tipo_limite,
					'limite_actual' => (string)$vinculo->limite_actual,
					'puesto' => (string)$vinculo->puesto,
					'estable' => (string)$vinculo->estable,
					'nacimiento' => (string)$vinculo->nacimiento,
				];
			}
			return $links;
		}
		/**
		 * Consulta el límite de crédito de una ficha
		 *
		 * @param array       $args [rfc, cartaBase64, extensionImagen]
		 * @param string|NULL $env  Ambiente en el que se va a trabajar
		 *
		 * @return bool|array Devuelve los fichas para consulta de límite
		 */
		public function consultaLimite ( array $args, string $env = NULL ): bool|array {
			$this->environment = $env === NULL ? $this->environment : $env;
			$url = strtoupper ( $this->environment ) === 'SANDBOX' ? $this->sandboxUrl : $this->liveUrl;
			$endpoint = 'CONSULTALIMITE';
			$data =
				"<ficha>{$args['ficha']}</ficha>
                <rfc>{$args['rfc']}</rfc>
                <nomina>{$args['nomina']}</nomina>
                <clave>{$args['clave']}</clave>";
			$res = $this->sendRequest ( $url, $data, $endpoint );
			libxml_use_internal_errors ( TRUE );
			$xml = simplexml_load_string ( $res );
			if ( $xml === FALSE ) {
				return [ FALSE, 'Error al consultar Blue Bull' ];
			}
			$transactionArray = [];
			foreach ( $xml->transaccion->children () as $child ) {
				$transactionArray[ $child->getName () ] = (string)$child;
			}
			return $transactionArray;
		}
		public
		function sendRequest ( string $url, string $data, string $endpoint ): bool|string {
			$curl = curl_init ();
			curl_setopt_array ( $curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => TRUE,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => [
					'transaccion' =>
						"<transacciones>
							<transaccion type='$endpoint'>
								<id>1</id>
								<login>VATORO.INTEGRADOR</login>
								<contrasena>#0987#qrte12</contrasena>
								$data
							</transaccion>
							</transacciones>" ],] );
			$response = curl_exec ( $curl );
			curl_close ( $curl );
			return $response;
		}
	}