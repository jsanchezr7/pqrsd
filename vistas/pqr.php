<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: login.php"); 
}
else
{
  require 'header.php';
if ($_SESSION['Admin']==1 OR $_SESSION['Usuario']==1)
{

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><a href="" onclick="refresh()" style=" color: black;
    text-decoration: none;" title="Refrescar pesta&ntilde;a">PQRSD</a></h1>
          </div>
          <script type="text/javascript">
            function refresh() {              
              window.location.reload();
            }
          </script>
          <div class="col-sm-6">
            <div class="breadcrumb float-sm-right">
              <h1 id="botonA" style="margin-right: 15px;"><span style="font-size:20px;">Consultar a BTE </span><a href="bte.php"><button id="btnagregar" class="btn btn-success"><i class="fa fa-plus-circle"></i> Ir</button></a></h1>
              <h1 id="botonB"><span style="font-size:20px;"> Nuevo ingreso</span>
                  <button id="btnagregar" class="btn btn-success" onclick="mostrarform('form1');$('#nav-resp-tab').hide();$('#nav-ciu-tab, #nav-ciu').addClass('active');$('#nav-pqr-tab, #nav-pqr, #nav-resp-tab, #nav-resp').removeClass('active');$('.ident, #nav-ciu-tab').show();"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                  <button id="btnvolver" class="btn btn-danger" onclick="cancelarform()"><i class="fa fa-arrow-circle-left"></i> Volver</button>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Formulario PQRs</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                  </button> -->
                </div>
              </div>
              
              <div class="card-body">
                <div id="listadoregistros">
                  <div class="row" style="margin-bottom:30px;">
                    <div class="col-2">
                      <label>Origen PQRSD:</label>
                      <select class="form-control" id="origen_pqr">
                        <option value="">Seleccione una opción</option>
                        <option value="Metro Bogotá">Metro Bogotá</option>
                        <option value="Metro Línea 1">Metro Línea 1</option>
                        <option value="Línea 2">Línea 2</option>
                      </select>
                    </div>
                    <div class="col-2">
                    <label>Canal:</label>
                    <select class="form-control" data-live-search="true" id="sel_canal"></select>
                  </div>
                  <div class="col-2">
                    <label>Estados:</label>
                    <select class="form-control" data-live-search="true" id="estados_filtro"></select>
                  </div>
                  
                  
                  <div class="col-2">
<label>Fecha Ingreso:</label>
<input type="date" class="form-control" id="fecha_crea">
</div>
<div class="col-2">
<label>Fecha Inicio (vencimiento):</label>
<input type="date" class="form-control" id="fecha_ini">
</div>
<div class="col-2">
<label>Fecha Fin (vencimiento):</label>
<input type="date" class="form-control" id="fecha_fin">
</div>
<div class="col-2">
<button type="button" class="btn btn-info" style="margin-top: 15px" onclick="listar();">Filtrar</button>
</div>
                  
                  
                </div>
                      <table id="tbllistado" class="table table-bordered table-hover" style="table-layout: auto !important;width: 100% !important;">
                          <thead>
                          <th>ID PQRSD</th>
                          <th>ID EMB</th>
                          <th>ID BTE</th>
                          <th>Observaciones</th>
                          <th>Origen</th>
                          <th>Asunto</th>
                          <th>Fecha Ingreso</th>
                          <th>Fecha de Vencimiento Ley</th>
                          <th>Estado</th>
                          <th>Estado interno trámite</th>
                          <th>Documento Ciudadano</th>
                          <th>Nombre Ciudadano</th>
                          <th>Estado Asignación</th>
                          <th>Opciones</th>
                          </thead>
                          <tbody>

                            
                          </tbody>
                          <!-- <tfoot>
                          <th>ID PQR</th>
                          <th>ID BTE</th>
                          <th>Tema</th>
                          <th>Asunto</th>
                          <th>Fecha Ingreso</th>
                          <th>Fecha de Vencimiento Ley</th>
                          <th>Estado</th>
                          <th>Documento Ciudadano</th>
                          <th>Nombre Ciudadano</th>
                          <th>Opciones</th>
                          </tfoot> -->
                        </table>
                      </div>
                        
                          <div class="panel-body"  id="formularioregistros">
                            <div class="row form" style="margin-bottom: 15px;">
                              <div class="col-4">
                                <label>Tipo petición:</label>
                                <select class="form-control" data-live-search="true" id="anonim" name="anonim" onchange="anonimo_form(this.value);" required>
                                  <option value="0" selected>Identificado</option>
                                  <option value="1">Anónimo</option>
                                </select>
                              </div>
                              <div class="col-12"><hr></div>
                            </div>

                            <nav class="form">
                              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-ciu-tab" data-toggle="tab" href="#nav-ciu" role="tab" aria-controls="nav-ciu" aria-selected="true">Peticionario</a>
                                <a class="nav-item nav-link" id="nav-pqr-tab" data-toggle="tab" href="#nav-pqr" role="tab" aria-controls="nav-pqr" aria-selected="false">Petición</a>
                                <a class="nav-item nav-link" id="nav-resp-tab" data-toggle="tab" href="#nav-resp" role="tab" aria-controls="nav-resp" aria-selected="false">Respuesta</a>
                              </div>
                            </nav>

                            <div class="tab-content form" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-ciu" role="tabpanel" aria-labelledby="nav-ciu-tab">
                            <form name="formulario_ciu" id="formulario_ciu" onsubmit="return false" method="POST">
                            <div class="row" style="margin-bottom: 15px;margin-top: 25px;">
                              <div class="col-2 ">
                             <label>Tipo de persona: *</label>
                            <select class="form-control data_ciu" data-live-search="true" id="idTipoPersona" name="idTipoPersona">
                              <option value="1" selected>Natural</option>
                              <option value="2">Juridica</option>
                              <option value="3">Establecimiento comercial</option>
                            </select>
                          </div>
                              <div class="col-2 "> 
                            <label>Tipo de identificación: </label>
                            <select class="form-control" data-live-search="true" id="codigoTipoIdentificacion" name="codigoTipoIdentificacion">
                            </select>
                          </div>
                              <div class="col-3 ">
                            <label>Número identificación: </label>
                            <input type="hidden" class="form-control" name="id_ciu" id="id_ciu">
                            <input type="text" class="form-control" name="numeroDocumento" onchange="filtro(this.value);" onkeydown="return /[^a-z]/.test(event.key)" id="numeroDocumento" placeholder="Número de identificación">
                          </div>
                          <div class="col-2">
                            <label id="nombre">Primer nombre: *</label>
                            <input type="text" class="form-control data_ciu" onkeydown="return /[^0-9]/.test(event.key)" name="primerNombre" id="primerNombre" placeholder="Primer nombre" required>
                          </div>
                          <div class="col-2 mod_ciu">
                            <label>Segundo nombre:</label>
                            <input type="text" class="form-control" onkeydown="return /[^0-9]/.test(event.key)" name="segundoNombre" id="segundoNombre" placeholder="Segundo nombre">
                          </div>
                          <div class="col-2 mod_ciu">
                            <label>Primer apellido:</label>
                            <input type="text" class="form-control" onkeydown="return /[^0-9]/.test(event.key)" name="primerApellido" id="primerApellido" placeholder="Primer apellido">
                          </div>
                          <div class="col-2 mod_ciu">
                            <label>Segundo apellido:</label>
                            <input type="text" class="form-control" onkeydown="return /[^0-9]/.test(event.key)" name="segundoApellido" id="segundoApellido" placeholder="Segundo apellido">
                          </div>
                          <div class="col-2 mod_ciu">
                            <label>Fecha de nacimiento:</label>
                            <input type="date" class="form-control" max="<?php echo date("Y-m-d", strtotime(date("Y-m-d"). ' - 6 months')); ?>" name="fechaNacimiento" id="fechaNacimiento">
                          </div>
                          <div class="col-2 mod_ciu">
                            <label>Género:</label>
                            <select class="form-control" data-live-search="true" id="idGenero" name="idGenero"></select>
                          </div>
                          <div class="col-2 verifica">
                            <label>Dígito de verificación:</label>
                            <input type="number" class="form-control" name="digitoVerificacion" id="digitoVerificacion" placeholder="Dígito de verificación (NIT)">
                          </div>
                          <div class="col-2 juridica entidad">
                            <label>Tipo de entidad:</label>
                            <select class="form-control" data-live-search="true" id="tipoPersonaJuridica" name="tipoPersonaJuridica"></select>
                          </div>
                          <div class="col-2 juridica entidad">
                            <label>Tipo de Empresa/Sociedad:</label>
                            <select class="form-control" data-live-search="true" id="codigoTipoEntidad" name="codigoTipoEntidad"></select>
                          </div>
                          <div class="col-2 ent_prv">
                            <label>Código entidad privada:</label>
                            <input type="number" class="form-control" name="codigoEntidadPrivada" id="codigoEntidadPrivada" placeholder="Celular contacto">
                          </div>
                          <div class="col-2">
                            <label>Teléfono:</label>
                            <input type="text" class="form-control" name="telefonoFijo" id="telefonoFijo" placeholder="Número de Teléfono">
                          </div>
                          <div class="col-2">
                            <label>Celular:</label>
                            <input type="text" class="form-control" name="celular" id="celular" placeholder="Número de Celular">
                          </div>
                          <div class="col-2">
                            <label>Pbx:</label>
                            <input type="text" class="form-control" name="pbx" id="pbx" placeholder="Pbx">
                          </div>
                          <br>
<!-- ___________________________________________________________________________________________________________________________________________________________ -->
                          <div class="col-12 " style="margin: 15px 0px 15px 0px;"><hr></div>
                          <br>
                          <div class="col-2 mod_ciu">
                            <label>Atención preferencial:</label>
                            <select class="form-control" data-live-search="true" id="idAtencionPreferencial" name="idAtencionPreferencial"></select>
                          </div>
                          <div class="col-2 mod_ciu">
                            <label>Pertenencia étnica:</label>
                            <select class="form-control" data-live-search="true" id="idEtnia" name="idEtnia"></select>
                          </div>
                          <div class="col-2 mod_ciu">
                            <label>Persona con discapacidad:</label>
                            <select class="form-control" data-live-search="true" id="idDiscapacidad" name="idDiscapacidad"></select>
                          </div>
                          <div class="col-2 mod_ciu">
                            <label>Orientación sexual:</label>
                            <select class="form-control" data-live-search="true" id="idOrientacionSexual" name="idOrientacionSexual">
                              <option value="">No especifíca</option>
                              <option value="3">Heterosexual</option>
                              <option value="1">Homosexual</option>
                              <option value="2">Bisexual</option>
                              <option value="4">Otra</option>
                            </select>
                          </div>
                          <div class="col-2 mod_ciu">
                            <label>Embarazo:</label>
                            <select class="form-control" data-live-search="true" id="idEmbarazo" name="idEmbarazo">
                              <option value="4">No brinda información</option>
                              <option value="1">1 a 3 meses</option>
                              <option value="2">4 a 6 meses</option>
                              <option value="3">7 a 9 meses</option>
                            </select>
                          </div>
                          <div class="col-2 mod_ciu">
                            <label>Adulto mayor:</label>
                            <input type="checkbox" class="form-control" style="width:20px;" name="adultoMayor" id="adultoMayor" value="1">
                          </div>

                          <div class="col-2 ">
                            <label>Notificación electrónica:</label>
                            <input type="checkbox" class="form-control" onchange="this.checked?$('.email').show()+$('#correoElectronico').prop('required', true):$('.email').hide()+$('#correoElectronico').prop('required', false);" style="width:20px;" name="notificacionElectronica" id="notificacionElectronica" value="1">
                          </div>
                          <div class="col-4 email">
                            <label>Email: *</label>
                            <input type="email" class="form-control" name="correoElectronico" id="correoElectronico" placeholder="Dirección de correo">
                          </div>
                          
                          <div class="col-2 ">
                            <label>Notificación física:</label>
                            <input type="checkbox" class="form-control" onchange="this.checked?$('.ubicacion').show()+$('#direccionResidencia').prop('required', true):$('.ubicacion').hide()+$('#direccionResidencia').prop('required', false);" style="width:20px;" name="notificacionFisica" id="notificacionFisica" value="1">
                          </div>
                          <br>
<!-- ___________________________________________________________________________________________________________________________________________________________ -->
                          <div class="col-12 ubicacion" style="margin: 15px 0px 15px 0px;"><hr></div>
                          <br>
                          <div class="col-2 ubicacion">
                            <label>Localidad:</label>
                            <select class="form-control" data-live-search="true" id="localidad" name="localidad"></select>
                          </div>
                          <div class="col-2 ubicacion">
                            <label>UPZ:</label>
                            <select class="form-control" data-live-search="true" id="codigoUpz" name="codigoUpz"></select>
                          </div>
                          <div class="col-2 ubicacion">
                            <label>Barrio:</label>
                            <select class="form-control" data-live-search="true" id="idBarrio" name="idBarrio"></select>
                          </div>
                          <div class="col-2 ubicacion">
                            <label>Estrato:</label>
                            <select class="form-control" data-live-search="true" id="idEstrato" name="idEstrato">
                              <option value="">Selecciona una opción</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                            </select>
                          </div>
                          <div class="col-4 ubicacion">
                            <label>Dirección: *</label>
                            <input type="text" class="form-control" name="direccionResidencia" id="direccionResidencia" placeholder="Dirección">
                          </div>
                          <div class="col-2 ubicacion">
                            <label>Código Postal:</label>
                            <input type="text" class="form-control" name="codigoPostal" id="codigoPostal" placeholder="Código Postal">
                          </div>
                          
                          <br>
<!-- ___________________________________________________________________________________________________________________________________________________________ -->
                          <div class="col-12 cont_mod" style="margin: 15px 0px 15px 0px;"><hr></div>
                          <br>

                          <div class="col-2 cont_mod">
                            <label>Nombre Contacto: *</label>
                            <input type="text" class="form-control cont" name="nombreCompletoContacto" id="nombreCompletoContacto" placeholder="Nombre completo contacto">
                          </div>
                          <div class="col-4 cont_mod">
                            <label>Dirección Contacto:</label>
                            <input type="text" class="form-control cont" name="direccionResidenciaContacto" id="direccionResidenciaContacto" placeholder="Dirección de residencia contacto">
                          </div>
                          <div class="col-2 cont_mod">
                            <label>Correo Contacto: *</label>
                            <input type="text" class="form-control cont" name="correoElectronicoContacto" id="correoElectronicoContacto" placeholder="Correo electrónico contacto">
                          </div>
                          <div class="col-2 cont_mod">
                            <label>Teléfono Contacto:</label>
                            <input type="text" class="form-control cont" name="telefonoFijoContacto" id="telefonoFijoContacto" placeholder="Teléfono fijo contacto">
                          </div>
                          <div class="col-2 cont_mod">
                            <label>Celular Contacto:</label>
                            <input type="text" class="form-control cont" name="celularContacto" id="celularContacto" placeholder="Celular contacto">
                          </div>
                          <div class="col-2 cont_mod">
                            <label>Cargo Contacto:</label>
                            <input type="text" class="form-control cont" name="cargoContacto" id="cargoContacto" placeholder="Cargo contacto">
                          </div>
                        
                          
                          <br>
                          <div class="col-12 " style="margin: 15px 0px 15px 0px;"><hr></div>
                          <br>
                        </div>
                        <button class="btn btn-primary" type="submit" id="btnGuardarciu"><i class="fa fa-save"></i> Guardar</button>
                        <button class="btn btn-danger" onclick="cancelarform()" id="btnCancelarfrom" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                     
                        </form>
                          </div>
<!-- _______________________________________________________________________________________________________________________________________________________________ 
_______________________________________________________________________________________________________________________________________________________________
_______________________________________________________________________________________________________________________________________________________________
_______________________________________________________________________________________________________________________________________________________________
_______________________________________________________________________________________________________________________________________________________________ -->

                        <div class="tab-pane fade show" id="nav-pqr" role="tabpanel" aria-labelledby="nav-pqr-tab">
                        <form name="formulario_pqr" id="formulario_pqr" method="POST">
                          <div class="row" style="margin-bottom: 15px;margin-top: 25px;">
                              <div class="col-4 ident">
                                <label>Tipo de solicitante: *</label>
                                <select class="form-control" data-live-search="true" id="codigoOpcion" name="codigoOpcion" required></select>
                              </div>
                              <div class="col-12 ident"><hr></div>
                            </div>
                          <div class="row" style="margin-bottom: 15px;">
                          <div class="col-4 id_bte">
                            <label>ID Bogotá Te Escucha:</label>
                            <input type="text" class="form-control" name="id_bte" id="id_bte" maxlength="50" readonly>
                          </div>

                          <div class="col-4">
                            <label>ID EMB:</label>
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="type_edit" id="type_edit">
                            <input type="text" class="form-control" name="id_emb" id="id_emb" maxlength="50" placeholder="ID EMB" >
                          </div>

                          <div class="col-4">
                            <label>Tipo Solicitud: *</label>
                            <select class="form-control" data-live-search="true" id="codigoTipoRequerimiento" name="codigoTipoRequerimiento" onchange="fechas();" required></select>
                          </div>

                          <div class="col-2">
                            <label>Tiene procedencia:</label>
                            <select class="form-control" data-live-search="true" id="tieneProcedencia" name="tieneProcedencia">
                              <option value="0">No</option>
                              <option value="1">Sí</option>
                            </select>
                          </div>

                          <div class="col-2">
                            <label>Es copia:</label>
                            <select class="form-control" data-live-search="true" id="esCopia" name="esCopia">
                              <option value="0">No</option>
                              <option value="1">Sí</option>
                            </select>
                          </div>

                          <div class="col-12">
                            <label>Asunto (BTE):</label>
                            <textarea type="text" class="form-control" name="asunto_obs" id="asunto_obs" maxlength="500" placeholder="Asunto" rows="3" ></textarea>
                          </div>

                          <br>
<!-- ___________________________________________________________________________________________________________________________________________________________ -->
                          <div class="col-12 " style="margin: 15px 0px 15px 0px;"><hr></div>
                          <br>

                          <div class="col-4">
                            <label>Dependencia (entidad que atiende):</label>
                            <select class="form-control" data-live-search="true" id="codigoDependencia" name="codigoDependencia" ></select>
                          </div>

                          <div class="col-4">
                            <label>Proceso de calidad:</label>
                            <select class="form-control" data-live-search="true" id="codigoProcesoCalidad" name="codigoProcesoCalidad" >
                              <option value="225">Apoyo</option>
                              <option value="224">Estratégico</option>
                              <option value="223">Misional</option>
                            </select>
                          </div>

                          <div class="col-4">
                            <label>Canal de Recepción: *</label>
                            <select class="form-control" data-live-search="true" id="codigoCanal" name="codigoCanal" required></select>
                          </div>

                          <div class="col-4 red">
                            <label>Red social:</label>
                            <select class="form-control" data-live-search="true" id="codigoRedSocial" name="codigoRedSocial">
                              <option value="4">Chat</option>
                              <option value="7">Google Forms</option>
                              <option value="2">Google Plus</option>
                              <option value="5">Instagram</option>
                              <option value="6">Teams</option>
                              <option value="3">Twitter</option>
                            </select>
                          </div>
                          
                          <div class="col-4">
                            <label>Radicado:</label>
                            <input type="text" class="form-control" name="numeroRadicado" id="numeroRadicado" placeholder="Radicado Entrada" >
                          </div>

                          <div class="col-4">
                            <label>Fecha Radicado:</label>
                            <input type="date" class="form-control" name="fechaRadicado" min="<?php echo date("Y-m-d", strtotime(date("Y-m-d"). ' - 6 months')); ?>" id="fechaRadicado">
                          </div>

                          <div class="col-4">
                            <label>Folios:</label>
                            <input type="number" class="form-control" oninput="this.value = Math.abs(this.value)" name="numeroFolios" id="numeroFolios" placeholder="Folios" >
                          </div>

                          <div class="col-12">
                            <label>Observaciones:</label>
                            <textarea type="text" class="form-control" name="observaciones" id="observaciones" maxlength="500" placeholder="Observaciones" rows="2"></textarea>
                          </div>

                          <br>
<!-- ___________________________________________________________________________________________________________________________________________________________ -->
                          <div class="col-12 " style="margin: 15px 0px 15px 0px;"><hr></div>
                          <div class="col-12 " style="margin: 0px 0px 15px 0px;"><h5 style="font-weight: bold;">Dirección de los hechos</h5></div>
                          <br>

                          <div class="col-2">
                            <label>Localidad:</label>
                            <select class="form-control" data-live-search="true" id="codigoLocalidadPeticion" name="codigoLocalidadPeticion"></select>
                          </div>

                          <div class="col-2">
                            <label>UPZ:</label>
                            <select class="form-control" data-live-search="true" id="codigoUpzPeticion" name="codigoUpzPeticion"></select>
                          </div>

                          <div class="col-2">
                            <label>Barrio:</label>
                            <select class="form-control" data-live-search="true" id="codigoBarrioPeticion" name="codigoBarrioPeticion"></select>
                          </div>

                          <div class="col-2">
                            <label>Estrato:</label>
                            <select class="form-control" data-live-search="true" id="codigoEstratoPeticion" name="codigoEstratoPeticion">
                              <option value="0">Selecciona una opción</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                            </select>
                          </div>

                          <div class="col-4">
                            <label>Dirección de los hechos:</label>
                            <input type="text" class="form-control" name="direccionHechos" id="direccionHechos" placeholder="Dirección" >
                          </div>

                          <div class="col-4">
                            <label>Ubicación aproximada:</label>
                            <input type="text" class="form-control" name="ubicacionAproximada" id="ubicacionAproximada" placeholder="Dirección" >
                          </div>


                          <br>
<!-- ___________________________________________________________________________________________________________________________________________________________ -->
                          <div class="col-12 " style="margin: 15px 0px 15px 0px;"><hr></div>
                          <br>


<!-- 
                          <div class="col-4">
                            <label>Entidad distrital:</label>
                            <select class="form-control" data-live-search="true" id="entidadDistrital" name="entidadDistrital"></select>
                          </div> -->

                          <div class="col-4">
                            <label>Fecha de recibido en la entidad:</label>
                            <input type="date" class="form-control" name="fecha_crea" id="fecha_crea" onchange="fechas();">
                          </div>

                          <div class="col-4">
                            <label>Grupo de Interés:</label>
                            <select class="form-control" data-live-search="true" id="group_int" name="group_int">
                              <option value="">Seleccione una opción</option>
                              <option value="Ciudadanía">Ciudadanía</option>
                              <option value="Población directamente afectada">Población directamente afectada</option>
                              <option value="Control ciudadano">Control ciudadano</option>
                              <option value="Organos de control">Organos de control</option>
                              <option value="Contro Político">Contro Político</option>
                              <option value="Sector Gobierno">Sector Gobierno</option>
                              <option value="Medios de comunicación">Medios de comunicación</option>
                              <option value="Gremios y proveedores">Gremios y proveedores</option>
                              <option value="Ambiental">Ambiental</option>
                              <option value="Provedores de Capital">Provedores de Capital</option>
                              <option value="Academía e investigación">Academía e investigación</option>
                              <option value="Junta directiva ">Junta directiva </option>
                              <option value="Colaboradores del metro">Colaboradores del metro</option>
                            </select>
                          </div>

                           <div class="col-4">
                            <label>Origen PQR:</label>
                            <select class="form-control" data-live-search="true" id="ori_pqr" name="ori_pqr">
                              <option value="">Seleccione una opción</option>
                              <option value="Metro Bogotá">Metro Bogotá</option>
                              <option value="Metro Línea 1">Metro Línea 1</option>
                              <option value="Línea 2">Línea 2</option>
                            </select>
                          </div>

                          <div class="col-4">
                            <label>Estado:</label>
                            <select class="form-control" data-live-search="true" id="estado" name="estado"></select>
                          </div>

                          <div class="col-4">
                            <label>Estado trámite:</label>
                            <select class="form-control" data-live-search="true" id="estado_tra" name="estado_tra" onchange="this.value==25?$('#fecha_ley').prop('readonly', false):$('#fecha_ley').prop('readonly', true);"></select>
                          </div>

                          <div class="col-4">
                            <label>Fecha Asignación:</label>
                            <input type="date" class="form-control" name="fecha_asigna" id="fecha_asigna" >
                          </div>

                          <div class="col-4">
                            <label>Fecha Término de Ley:</label>
                            <input type="date" class="form-control" name="fecha_ley" id="fecha_ley" readonly>
                          </div>
<!-- ___________________________________________________________________________________________________________________________________________________________ -->
                            <br>
                            <div class="col-12 anonimo" style="margin: 15px 0px 15px 0px;"><hr></div>
                            <br>
                            <div class="col-2 anonimo ">
                              <label>Atención preferencial:</label>
                              <select class="form-control" data-live-search="true" id="idAtencionPreferencial_anonim" name="idAtencionPreferencial"></select>
                            </div>
                            <div class="col-2 anonimo ">
                              <label>Nombre Contacto:</label>
                              <input type="text" class="form-control" name="nombreCompletoContacto" id="nombreCompletoContacto_anonim" placeholder="Nombre completo contacto">
                            </div>                          
                            <div class="col-2 anonimo ">
                              <label>Teléfono Contacto:</label>
                              <input type="text" class="form-control" name="telefonoFijoContacto" id="telefonoFijoContacto_anonim" placeholder="Teléfono fijo contacto">
                            </div>
                            <div class="col-2 anonimo ">
                              <label>Celular Contacto:</label>
                              <input type="text" class="form-control" name="celularContacto" id="celularContacto_anonim" placeholder="Celular contacto">
                            </div>
                            <div class="col-2 anonimo">
                              <label>Notificación electrónica:</label>
                              <input type="checkbox" class="form-control" onchange="this.checked?$('.email_anonim').show()+$('#correoElectronicoContacto_anonim').prop('required', true):$('.email_anonim').hide()+$('#correoElectronicoContacto_anonim').prop('required', false);" style="width:20px;" name="notificacionElectronica" id="notificacionElectronica_anonim" value="1">
                            </div>
                            <div class="col-2 email_anonim">
                              <label>Correo Contacto: *</label>
                              <input type="text" class="form-control" name="correoElectronicoContacto" id="correoElectronicoContacto_anonim" placeholder="Correo electrónico contacto">
                            </div>
                            
                            <div class="col-2 anonimo">
                              <label>Notificación física:</label>
                              <input type="checkbox" class="form-control" onchange="this.checked?$('.ubicacion_anonim').show()+$('#direccionResidenciaContacto_anonim').prop('required', true):$('.ubicacion_anonim').hide()+$('#direccionResidenciaContacto_anonim').prop('required', false);" style="width:20px;" name="notificacionFisica" id="notificacionFisica_anonim" value="1">
                            </div>

                            <div class="col-4 ubicacion_anonim">
                              <label>Dirección Contacto: *</label>
                              <input type="text" class="form-control" name="direccionResidenciaContacto" id="direccionResidenciaContacto_anonim" placeholder="Dirección de residencia contacto">
                            </div>

<!-- ___________________________________________________________________________________________________________________________________________________________ -->
                            <br>
                            <div class="col-12 ponderantes" style="margin: 15px 0px 15px 0px;"><hr></div>
                            <br>

                            <div class="col-2 ponderantes">
                              <label>Agregar Poderdante:</label>
                              <button class="btn btn-info" type="button" onclick="add_pond();" style="margin-bottom: 10px;">Añadir</button>
                            </div>

                            <div class="col-12 ponderantes">
                              <div class="row" id="list_pond">
                                
                              </div>
                            </div>
<!-- ___________________________________________________________________________________________________________________________________________________________ -->

                            <br>
                            <div class="col-12 representados" style="margin: 15px 0px 15px 0px;"><hr></div>
                            <br>

                            <div class="col-2 representados">
                              <label>Agregar Representado:</label>
                              <button class="btn btn-info" type="button" onclick="add_repr();" style="margin-bottom: 10px;">Añadir</button>
                            </div>

                            <div class="col-12 representados">
                              <div class="row" id="list_repr">
                                
                              </div>
                            </div>
<!-- ___________________________________________________________________________________________________________________________________________________________ -->
                          <br>
                          <div class="col-12" style="margin: 15px 0px 15px 0px;"><hr></div>
                          <br>

                          <div class="col-4 anexo">
                            <label>Anexos</label>
                            <input type="file" class="form-control multi" id="anexos" data-show-upload="false" data-show-caption="true" multiple>
                          </div>

                          <div class="col-12 anexo" style="margin-top:10px;">
                            <table class="table table-responsive">
                            <thead>
                            <tr><th>Archivos seleccionados</th><th>Eliminar</th></tr>
                          </thead>
                          <tbody id="list_anexo">
                            
                          </tbody>
                          </table>
                          </div>
                          <div class="row" style="margin-top:30px;">
                            <div class="col-12">
                              <div id="hr_edit"></div>
                              <div class="row" id="entre_edit"></div>
                              <br>
                            </div>
                          </div>



                        <script type="text/javascript">
                            var anexos = [];

                            $('#anexos').change(function(){
                              for (var i = 0; i < this.files.length; i++){
                                var fileName = this.files[i].name;
                                var file = this.files[i];
                                var fileExtension = fileName.split('.').pop();
 
                                if (!allowedExtensions.some(function (ext) {
                                    return ext.toLowerCase() === fileExtension.toLowerCase();
                                })) {
                                    alert("El archivo con extensión '" + fileExtension.toUpperCase() + "' no está permitido.");
                                }else{
                                  anexos.push(file);
                                  tbl_an();
                                }
                              }
                              $("#anexos").val("");
                            });
                        </script>

                          
                          
                          

                        </div>
                         

                <button class="btn btn-primary" onclick="$('#type_edit').val(0);" type="submit" id="btnGuardarfrom"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger" onclick="cancelarform()" id="btnCancelarfrom" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                <button class="btn btn-success" onclick="$('#type_edit').val(1);" id="edit_bte" type="submit"><i class="fa fa-arrow-circle-left"></i> Envío a BTE</button>
                     
              </form>
            </div>


            <!-- _______________________________________________________________________________________________________________________________________________________________ 
_______________________________________________________________________________________________________________________________________________________________
_______________________________________________________________________________________________________________________________________________________________
_______________________________________________________________________________________________________________________________________________________________
_______________________________________________________________________________________________________________________________________________________________ -->



            <div class="tab-pane fade show" id="nav-resp" role="tabpanel" aria-labelledby="nav-resp-tab">
              <div class="row" style="margin-bottom:30px;">
                <div class="col-6">
                  <br>
                  <div id="hr_view_r"></div>
                  <div class="row" id="entre_view_r"></div>
                  <br>
                </div>
              </div>

                <h5>RESPUESTA</h5>
                <br>
                <div class="row">
                  <div class="col-4">
                    <label>Adjuntos</label>
                    <input type="file" class="form-control multi" name="adjuntos[]" id="adjuntos" data-show-upload="false" data-show-caption="true" multiple>
                  </div>

                  <div class="col-12" style="margin-top:10px;">
                    <table class="table table-responsive">
                      <thead>
                        <tr><th>Archivos seleccionados</th><th>Eliminar</th></tr>
                      </thead>
                      <tbody id="list_ad">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
                <form name="entrega_view" id="entrega_view" method="POST"  style="margin-bottom:30px;">
                  <div class="row" >
                    <div class="col-4">
                      <label>Estado:</label>
                      <select class="form-control" data-live-search="true" id="estado_view" name="estado_view" required>
                        <option value="3">Terminado</option>
                        <option value="5">Cierre con acta de compromiso</option>
                      </select>
                    </div>
                    <div class="col-4">
                      <label>Observaciones:</label>
                      <input type="hidden" name="id_pqr2" id="id_pqr2">
                      <input type="hidden" name="type_sub" id="type_sub">
                      <textarea type="text" class="form-control" name="observa_view" id="observa_view" maxlength="500" placeholder="Observaciones" rows="2" required></textarea>
                    </div>
                    <div class="col-3">
                      <label>Fecha radicado:</label>
                      <input type="date" class="form-control cierre" min="<?php echo date("Y-m-d", strtotime(date("Y-m-d"). ' - 3 months')); ?>" max="<?php echo date("Y-m-d"); ?>" name="date_respc" id="date_respc" required>
                    </div>
                    <div class="col-3">
                      <label>Radicado respuesta:</label>
                      <input type="text" class="form-control cierre" name="rad_respc" id="rad_respc" maxlength="20" placeholder="Radicado de respuesta" required>
                    </div>
                    <div class="col-3">
                      <label>Categoría:</label>
                      <select class="form-control cierre" data-live-search="true" id="categoria" name="categoria" required></select>
                    </div>
                    <div class="col-3">
                      <label>Subtema:</label>
                      <select class="form-control cierre" data-live-search="true" id="subtema" name="subtema" required></select>
                    </div>
                  </div>
                  <br>

                  <button class="btn btn-primary" type="submit" id="btnEntrega_view" onclick="$('#type_sub').val(0);"><i class="fa fa-save"></i> Guardar</button>
                  <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                  <button class="btn btn-success" type="submit" id="btnenviobte" onclick="$('#type_sub').val(1);"><i class="fa fa-save"></i> Envío a BTE</button>
                       
                </form>

                <div id="obs_asig">
                  <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Observaciones</a>
                      <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Asignaciones</a>
                    </div>
                  </nav>
                  <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                      <br>
                      <h5>Observaciones realizadas</h5>
                      <br>
                      <table class="table table-responsive">
                        <thead>
                          <tr><th>Observación</th><th>Fecha/Hora</th></tr>
                        </thead>
                        <tbody id="entre_obs">
                        
                        </tbody>
                      </table>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                      <br>
                      <h5>Historial de Asignaciones</h5>
                      <br>
                      <table class="table table-responsive">
                        <thead>
                          <tr><th>Quién asignó</th><th>Asignado a</th><th>Observación</th><th>Fecha</th></tr>
                        </thead>
                        <tbody id="list_asign">
                          
                        </tbody>
                      </table>
                    </div> 
                  </div>
                </div>


            <script type="text/javascript">
                  var adjuntos = [];

                  $('#adjuntos').change(function(){
                    for (var i = 0; i < this.files.length; i++){
                      console.log(i)
                      var fileName = this.files[i].name;
                      console.log(fileName)
                      var file = this.files[i];
                      var fileExtension = fileName.split('.').pop();
 
                      if (!allowedExtensions.some(function (ext) {
                          return ext.toLowerCase() === fileExtension.toLowerCase();
                      })) {
                          alert("El archivo con extensión '" + fileExtension.toUpperCase() + "' no está permitido.");
                      }else{
                        adjuntos.push(file);
                        tbl_ad();
                      }
                    }
                    $("#adjuntos").val("");
                  });
            </script>   
                
                
                
                

            </div>
    </div>










              </div>
            </div>

            



              






              <div class="modal fade" id="asignar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Asignación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cancelarmodal()">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    <form name="asignacion" id="asignacion" method="POST">
                  <div class="row" >
                    <div class="col-6">
                    <label>Área:</label>
                    <select class="form-control" data-live-search="true" id="area" name="area" required="required"></select>
                  </div>
                  <div class="col-6">
                    <label>Usuario para asignar:</label>
                    <input type="hidden" name="id_pqrm" id="id_pqrm">
                    <select class="form-control" data-live-search="true" onchange="add_asign(this.value);" id="user"></select>
                  </div>
                  <div class="col-6" id="t_people">
                    
                  </div>
                  <div class="col-6 obs_form">
                    <label>Observaciones:</label>
                    <input type="text" class="form-control" name="observa_asign" id="observa_asign" maxlength="500" placeholder="Observaciones">
                  </div>
                </div>
              <br>
              <script type="text/javascript">
                var people = [];
              </script>
              <hr>
                <button class="btn btn-primary" type="submit" id="btnAsigna"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger" data-dismiss="modal" aria-label="Close" type="button" onclick="cancelarmodal();" ><i class="fas fa-times"></i> Cancelar</button>
              </form>

                  </div>
                </div>
              </div>
            </div>






            <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Asignación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cancelarform()">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" id="id_pqr_con">
                    <div id="asign"></div>
                  </div>
                  <div class="modal-footer">
                      <button class="btn btn-primary" type="button" onclick="confirmar()" id="btn_confirm"><i class="fa fa-check"></i> Confirmar Asignación</button>
                      <button class="btn btn-danger" type="button" onclick="cancel_asign()" id="btn_cancel"><i class="fas fa-times"></i> Cancelar Asignación</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="loading_modal">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-body">
              <img id="gif" src="pages/gif.gif" width="500">
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>




            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
    

  </div>
<script type="text/javascript">
    const store = <?php echo json_encode($_SESSION['JWT']);?>;
  </script>
  <script type="text/javascript" src="scripts/pqr.js"></script>
<?php
}
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>
<?php 
}
ob_end_flush();
?>