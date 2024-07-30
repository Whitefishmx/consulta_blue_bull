<div class="container mt-5">
	<div class="row">
		<div class="">
			<h2>Verificar RFC</h2>
			<form id="SearchRFC" name="SearchRFC">
				<div class="row">
					<div class="input-field inline col s5">
						<input
								id="rfc" name="rfc" type="text" class="validate" required data-length="13"
								minlength="12">
						<label for="rfc">RFC *</label>
					</div>
					<div class="file-field input-field col s7">
						<div class="btn">
							<span>Carta de autorización</span>
							<input id="letter" name="letter" type="file" accept="image/*">
						</div>
						<div class="file-path-wrapper">
							<label>
								<input id="letterLbl" class="file-path validate" type="text">
							</label>
						</div>
					</div>
				</div>
				<div class="row">
					<button id="btnSend" name="btnSend" type="submit" class="btn btn-dark blue">Validar RFC</button>
				</div>
			</form>
		</div>
	</div>
	<div id="result" class="row" style="display: none">
		<table class="centered highlight responsive-table">
			<thead>
			<tr>
				<th>Ficha</th>
				<th>RFC</th>
				<th>Nomina</th>
				<th>Clave</th>
				<th>Nombre</th>
				<th>Limite actual</th>
				<th>Puesto</th>
				<th>Estable</th>
				<th>Nacimiento</th>
			</tr>
			</thead>
			<tbody id="tBody"></tbody>
		</table>
	</div>
</div>
<script>
	$(document).ready(function () {
		const search = $("#SearchRFC");
		const divRes = $("#result");
		search.on("submit", function (e) {
			e.preventDefault();
			const formData = new FormData($("#formulario")[0]);
			const letter = $("#letter")[0].files[0];
			const rfc = $("#rfc").val();
			formData.append("letter", letter);
			formData.append("rfc", rfc);
			$.ajax({
				url: "/searchRfc",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function () {
					divRes.css("display", "none");
					const obj = search;
					const left = obj.offset().left;
					const top = obj.offset().top;
					const width = obj.width();
					const height = obj.height();
					$("#Loader").delay(50000).css({
						display: "block",
						opacity: 1,
						visibility: "visible",
						left: left,
						top: top,
						width: width,
						height: height,
						zIndex: 999999
					}).focus();
				},
				success: function (response) {
					let tr;
					let tbody = $("#tBody");
					tbody.empty();
					M.Toast.dismissAll();
					$.each(response, function (index, value) {
						tr += "<tr>" +
							"<td>" + value.ficha + "</td>" +
							"<td>" + value.rfc + "</td>" +
							"<td>" + value.nomina + "</td>" +
							"<td>" + value.clave + "</td>" +
							"<td>" + value.nombre + "</td>" +
							"<td>" + value.limite_actual + "</td>" +
							"<td>" + value.puesto + "</td>" +
							"<td>" + value.estable + "</td>" +
							"<td>" + value.nacimiento + "</td>" +
							"</tr>";
					});
					tbody.append(tr);
					divRes.css("display", "block");
					$("#rfc").val('');
					$("#letter").val('');
					$('#letterLbl').val('');
				},
				complete: function () {
					$("#Loader").css({
						display: "none"
					});
				},
				error: function (data) {
					divRes.css("display", "none");
					const errors = data.responseJSON.reason;
					M.Toast.dismissAll();
					if ((typeof errors) === "object") {
						$.each(errors, function (index, value) {
							toastHTML = "<span>" + value + "</span>" +
								"<button onclick='M.Toast.dismissAll()' class='btn-flat toast-action'>" +
								"<span class='material-icons' style='display: block; color: white;'>cancel</span></button>";
							M.toast({html: toastHTML, displayLength: 20000, duration: 20000});
						});
					} else {
						toastHTML = "<span>" + errors + "</span>" +
							"<button onclick='M.Toast.dismissAll()' class='btn-flat toast-action'>" +
							"<span class='material-icons' style='display: block; color: white;'>cancel</span></button>";
						M.toast({html: toastHTML, displayLength: 20000, duration: 20000});
					}
				}
			});
			
		});
	});
</script>