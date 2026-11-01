$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
    	var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
              	alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
    	$('#foto').val('');
    	$(".delPhoto").addClass('notBlock');
    	$("#img").remove();

        if($("#foto_actual") && $("#foto_remove")){
            $("#foto_remove").val('img_producto.jpg');
        }

    });

    //*******INICIO******* Modal Form Add Product
    $('.add_product').click(function(e){
        e.preventDefault();
        var producto = $(this).attr('product');
        var action  = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,producto:producto},

            success: function(response){
                console.log(response);
                if(response != 'error'){
                    var info = JSON.parse(response);
                    console.log(info);
                    $('#product_id').val(info.idProducto);
                    $('.nameProducto').html(info.nombre);
                }
            },
           
            error: function(error){
                console.log(error);
            }
        });
        $('.modal').fadeIn();
    });
    //*******FIN******* Modal Form Add Product

    //*******INICIO******* Modal Form cobrar
    $('.add_cobrar').click(function(e){
        e.preventDefault();
        var id_Cuota = $(this).attr('cuota');
        var id_cliente = $(this).attr('id_cliente');
        var id_venta = $(this).attr('id_Venta');
        var no_cuota = $(this).attr('no_cuota');
        var action  = 'infoCuotas';
        
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,id_Cuota:id_Cuota,id_venta:id_venta,no_cuota:no_cuota},

            success: function(response){
                //console.log(response);
                if(response != 'error'){
                    var info = JSON.parse(response);
                    console.log(info);
                    // $('#product_id').val(info.id);
                    //$('.nameProducto').html(info.nombre);
                    $interes = Math.round((info.monto * 0.0011)*info.diasAtrasados);
                    $cuota = info.valorMora;
                    console.log($cuota)
                
                    $mora = '0';
                    if(info.saldo > 0){
                        
                        $montoConMora = info.saldo; 
                    }else if(info.diasAtrasados > 0){
                            $montoConMora = Math.round($cuota);
                            $mora = $interes;
                        }else{
                            $montoConMora = info.monto;
                            $mora = 0;
                        }
                   
                    //alert(info.diasAtrasados);

                        $('.bodyModal').html('<form action="" method="post" name="form_cobrar" id="form_cobrar" onsubmit="event.preventDefault(); sendDataCobrar();">'+
                    '<h1><i class="fas fa-cubes" style="font-size: 45px;"></i> <br> Cobranza</h1>'+

                    '<label for="">Cuota No.</label>'+
                    '<input type="text" name="num_cuota" id="num_cuota" value="'+info.noCuota+'" disabled required>'+
                    '<label for="">Interes por Mora</label>'+
                    '<input type="text" name="mora" id="mora" value="'+numberWithCommas($mora)+'" required disabled>'+
                    '<label for="tipo">Exonerar Interes</label>'+
                    '<button name="no_exonerar_interes" id="no_exonerar_interes" type="button" class="btn btn-secondary" value="NO">NO</button>'+
                    ' '+
                    '<button name="exonerar_interes" id="exonerar_interes" type="button" class="btn btn-secondary" value="SI">SI</button>'+
                    '<label for="">Monto a cobrar</label>'+
                    '<input type="text" name="monto_a_cobrar" id="monto_a_cobrar" value="'+numberWithCommas($montoConMora)+'" required disabled>'+
                    '<label for="">Efectivo</label>'+
                    '<input type="text" name="efectivo" id="efectivo"  required>'+
                    '<label for="">Saldo</label>'+
                    '<input type="text" name="saldo" id="saldo" required>'+
                    '<label for="">Vuelto</label>'+
                    '<input type="text" name="vuelto" id="vuelto" required>'+
                    '<input type="hidden" name="id_cuota" id="id_cuota" value="'+info.id+'" required>'+
                    '<input type="hidden" name="montoRecibido" id="montoRecibido" value="'+info.montoRecibido+'" required>'+
                    '<input type="hidden" name="montoTotal" id="montoTotal" value="'+info.montoTotal+'" required>'+
                    '<input type="hidden" name="montoSaldo" id="montoSaldo" value="'+info.saldo+'" required>'+
                    '<input type="hidden" name="montoCuota" id="montoCuota" value="'+info.monto+'" required>'+
                    '<input type="hidden" name="action" id="action" value="cobrar_cuota" required>'+
                    '<input type="hidden" name="id_cliente" id="id_cliente" value="'+id_cliente+'" required>'+
                    '<input type="hidden" name="id_venta" id="id_venta" value="'+info.idVenta+'" required>'+
                    '<div class="alert alertAddProduct">'+

                    '</div>'+
                    '<button type="submit" class="btn_new"><i class="fas fa-plus"></i> Cobrar</button>'+
                    //'<button type="" class="btn_new"><i class="fas fa-plus"></i> Cobrar y Generar Factura</button>'+
                    '<a href="#" class="btn_ok closeModal" onclick="closeModal(); location.reload();"><i class="fas fa-ban"></i> Cerrar</a>'+

                '</form>' );
                    //INICIO: calculo para vuelto
                    $('#efectivo').keyup(function(e){
                        e.preventDefault();
                        var montoEfectivo = numberSinComas($('#monto_a_cobrar').val())
                        var montoRecibido = numberSinComas($(this).val());
                        var vuelto = (montoEfectivo - montoRecibido) * -1;
                        
                        $('#vuelto').val(numberWithCommas(vuelto));

                        var saldo = (montoEfectivo - montoRecibido);
                        if (saldo < 0){
                            $('#saldo').val('0');
                           
                        }else{
                            $('#saldo').val(numberWithCommas(saldo));
                            $('#vuelto').val('0');
                        }
                        
                        })
                    //FIN: calculo para vuelto
                    $('#exonerar_interes').click(function(e){
                        e.preventDefault();
                        var mCobrar = $('#montoCuota').val();
                        $('#monto_a_cobrar').val(numberWithCommas(mCobrar));

                    })

                    $('#no_exonerar_interes').click(function(e){
                        e.preventDefault();
                        // var mCobrar = $cuota;
                        // $('#monto_a_cobrar').val("");
                        $('#monto_a_cobrar').val(numberWithCommas($montoConMora));

                    })
                    
                    //para agregar un punto a miles.
                    $("#efectivo").on({
                       "focus": function(event) {
                         $(event.target).select();
                       },
                       "keyup": function(event) {
                         $(event.target).val(function(index, value) {
                           return value.replace(/\D/g, "")
                             .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                         });
                       }
                     });
                    //fin para agregar un punto a miles.

                    //funcion separador de miles.
                    function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }

                    function numberSinComas(number) { return number.toString().replace(/[.]/g, ''); }
                }else{
                    $('.bodyModal').html('<form>'+
                    '<h1> <br> Aviso!</h1>'+
                    '<p>Existe una cuota anterior sin cobrar, favor verificar'+
                    '<a href="#" class="btn_ok closeModal" onclick="closeModal(); location.reload();"><i class="fas fa-ban"></i> Cerrar</a>'+

                    '</form>'
                    );
                }
            },
            error: function(error){
                console.log(error);
            }
        });
        $('.modal').fadeIn();
    });
    //*******FIN******* Modal Form cobrar

    //*******INICIO******* Modal PARA BUSCAR PRODUCTO
    $('.buscar_producto').click(function (e) {
        e.preventDefault();
        var action = 'lista_productos';
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: { action: action },
            success: function (response) {
                console.log(response);
                if (response != 'error') {
                    //var info = JSON.parse(response);
                    // console.log(info);

                    $('.bodyModal').html(

                        '<div class="app">' +
                        '<input type="text" id="searchInput" placeholder="Filtrar por nombre producto..." />' +

                        ' <table class="table">' +
                        '<thead>' +
                        '<tr>' +
                        '<th>ID</th>' +
                        '<th>NOMBRE PRODUCTO</th>' +
                        '<th>Marca</th>' +
                        '<th>Precio Venta</th>' +
                        '<th>STOCK</th>' +
                        '<th>Acción</th>' +
                        '</tr>' +
                        '</thead>' +
                        '<tbody id="names">' +
                        '<tr>' +
                        '<td>Licuadora pkt f1000</td>' +
                        '<td>TOKIO</td>' +
                        '<td>150.000</td>' +
                        '<td>Seleccionar</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Samuel</td>' +
                        '<td>22</td>' +
                        '<td>Python</td>' +
                        '<td>Seleccionar</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Alegra</td>' +
                        '<td>28</td>' +
                        '<td>Ruby</td>' +
                        '<td>Seleccionar</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Juan</td>' +
                        '<td>29</td>' +
                        '<td>Python</td>' +
                        '<td><a href="#"> Seleccionar</a></td' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Daniel</td>' +
                        '<td>40</td>' +
                        '<td>JavaScript</td>' +
                        '<td>Seleccionar</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Maria</td>' +
                        '<td>21</td>' +
                        '<td>C</td>' +
                        '<td>Seleccionar</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>David</td>' +
                        '<td>26</td>' +
                        '<td>JavaScript</td>' +
                        '</tr>' +

                        '</tbody>' +
                        '</table>' +
                        '</div>' +
                        '<script src="index.js" type="text/javascript"></script>' +
                        '<a href="#" class="btn_ok closeModal" onclick="closeModal(); location.reload();"><i class="fas fa-ban"></i> Cerrar</a>' +

                        '</form>');

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
        $('.modal').fadeIn();
    });
        //*******FIN******* MODAL PARA BUSCAR PRODUCTO


    //*******INICIO******* Modal lista productos comprados
    $('.mostrar_lista_producto').click(function(e){
        e.preventDefault();
        var id_venta = $(this).attr('id_venta');
        var action  = 'lista_productos_comprados';
        
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,id_venta:id_venta},

            success: function(response){
                console.log(response);
                if(response != 'error'){
                    var info = JSON.parse(response);
                    console.log(info.detalle);
                    $('.bodyModal').html(
                    '<form action="" method="" name="" id="">'+
                        '<h1><i class="far fa-list-alt" style="font-size: 26px;"></i> EN CONCEPTO DE</h1>'+
                        '<table class="table">'+
                            '<thead>'+
                                '<tr>'+
                                '<th scope="col">Referecias</th>'+
                                '<th scope="col">Productos</th>'+
                                '</tr>'+
                            '</thead>'+
                            info.detalle+
                        '</table>'+
                        '<button type="button" class="btn btn-secondary" onclick="closeModal();"><i class="fas fa-times"></i> Cerrar</button>'+
                    '</form>' );
                }
            },
            error: function(error){
                console.log(error);
            }
        });
        $('.modal').fadeIn();
    });
    //*******FIN******* Modal lista de productos comprados

    //*******INICIO******* Modal Buscador de Producto
    $('.buscar_producto').click(function(e){
        e.preventDefault();
        //var id_Cuota = $(this).attr('cuota');
        //var action  = 'infoCuotas';
        
        //$.ajax({
          //  url: 'ajax.php',
           // type: 'POST',
            //async: true,
            //data: {action:action,id_Cuota:id_Cuota},

            //success: function(response){
                //console.log(response);
                //if(response != 'error'){
                   //var info = JSON.parse(response);
                    //console.log(info);
                    // $('#product_id').val(info.id);
                    //$('.nameProducto').html(info.nombre);
                   // $interes = Math.round((info.monto * 0.0011)*info.diasAtrasados);
                   // $cuota = info.valorMora;
                    //console.log($cuota)
                
                    //$mora = '0';
                    //if(info.saldo > 0){
                        
                       // $montoConMora = info.saldo; 
                   // }else if(info.diasAtrasados > 0){
                          // $montoConMora = Math.round($cuota);
                        //    $mora = $interes;
                       // }else{
                        //    $montoConMora = info.monto;
                        //    $mora = 0;
                       // }
                   
                    //alert(info.diasAtrasados);

                        $('.bodyModal').html('<form action="" method="post" name="form_cobrar" id="form_cobrar" onsubmit="event.preventDefault(); sendDataCobrar();">'+
                    '<h1><i class="fas fa-cubes" style="font-size: 45px;"></i> <br> Cobranza test</h1>'+

                    '<label for="">Cuota No.</label>'+
                    '<input type="text" name="num_cuota" id="num_cuota" value="'+info.noCuota+'" disabled required>'+
                    '<label for="">Interes por Mora</label>'+
                    '<input type="text" name="mora" id="mora" value="'+numberWithCommas($mora)+'" required disabled>'+
                    '<label for="tipo">Exonerar Interes</label>'+
                    '<button name="no_exonerar_interes" id="no_exonerar_interes" type="button" class="btn btn-secondary" value="NO">NO</button>'+
                    ' '+
                    '<button name="exonerar_interes" id="exonerar_interes" type="button" class="btn btn-secondary" value="SI">SI</button>'+
                  
                   
                    // '<select name="exonerar_interes" id="exonerar_interes">'+
                    //     '<option value="0">'+
                    //         'SI</option>'+
                    //     // '<option value="1">'+
                    //     //     'SI</option>'+
                    // '</select>'+
                    '<label for="">Monto a cobrar</label>'+
                    '<input type="text" name="monto_a_cobrar" id="monto_a_cobrar" value="'+numberWithCommas($montoConMora)+'" required disabled>'+
                    '<label for="">Efectivo</label>'+
                    '<input type="text" name="efectivo" id="efectivo"  required>'+
                    '<label for="">Saldo</label>'+
                    '<input type="text" name="saldo" id="saldo" required>'+
                    '<label for="">Vuelto</label>'+
                    '<input type="text" name="vuelto" id="vuelto" required>'+

                    '<input type="hidden" name="id_cuota" id="id_cuota" value="'+info.id+'" required>'+
                   
                    '<input type="hidden" name="montoRecibido" id="montoRecibido" value="'+info.montoRecibido+'" required>'+
                    '<input type="hidden" name="montoTotal" id="montoTotal" value="'+info.montoTotal+'" required>'+
                    '<input type="hidden" name="montoSaldo" id="montoSaldo" value="'+info.saldo+'" required>'+
                    '<input type="hidden" name="montoCuota" id="montoCuota" value="'+info.monto+'" required>'+
                    '<input type="hidden" name="action" id="action" value="cobrar_cuota" required>'+
                    '<div class="alert alertAddProduct">'+

                    '</div>'+
                    '<button type="submit" class="btn_new"><i class="fas fa-plus"></i> Cobrar</button>'+
                    '<button type="" class=""><i class="fas fa-plus"></i> Cobrar y Generar Factura</button>'+
                    '<a href="#" class="btn_ok closeModal" onclick="closeModal(); location.reload();"><i class="fas fa-ban"></i> Cerrar</a>'+

                '</form>' );
                    //INICIO: calculo para vuelto
                   // $('#efectivo').keyup(function(e){
                   //     e.preventDefault();
                   //     var montoEfectivo = numberSinComas($('#monto_a_cobrar').val())
                    //    var montoRecibido = numberSinComas($(this).val());
                    //    var vuelto = (montoEfectivo - montoRecibido) * -1;
                        
                    //    $('#vuelto').val(numberWithCommas(vuelto));

                    //    var saldo = (montoEfectivo - montoRecibido);
                     //   if (saldo < 0){
                     //       $('#saldo').val('0');
                           
                     //   }else{
                     //       $('#saldo').val(numberWithCommas(saldo));
                    //        $('#vuelto').val('0');
                    //    }
                        
                    //   })
                    //FIN: calculo para vuelto
                  //  $('#exonerar_interes').click(function(e){
                  //      e.preventDefault();
                  //      var mCobrar = $('#montoCuota').val();
                  //      $('#monto_a_cobrar').val(numberWithCommas(mCobrar));

                  //  })

                  //  $('#no_exonerar_interes').click(function(e){
                  //      e.preventDefault();
                        // var mCobrar = $cuota;
                        // $('#monto_a_cobrar').val("");
                   //     $('#monto_a_cobrar').val(numberWithCommas($montoConMora));

                   // })
                    
                    //para agregar un punto a miles.
                 //   $("#efectivo").on({
                 //      "focus": function(event) {
                 //        $(event.target).select();
                 //      },
                 //      "keyup": function(event) {
                  //       $(event.target).val(function(index, value) {
                  //         return value.replace(/\D/g, "")
                  //           .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                  //       });
                  //     }
                  //   });
                    //fin para agregar un punto a miles.

                    //funcion separador de miles.
                //    function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }

                //    function numberSinComas(number) { return number.toString().replace(/[.]/g, ''); }
               // }
           // },
          //  error: function(error){
           //     console.log(error);
        //    }
      //  });
        $('.modal').fadeIn();
    });
    //*******FIN******* Modal Buscador de Producto.



    

    $('#search_proveedor').change(function(e){
        e.preventDefault();

        var sistema = getUrl();
        location.href = sistema+'buscar_producto.php?proveedor='+$(this).val();
    })

    
    $('#search_categorias').change(function(e){
        e.preventDefault();

        var sistema2 = getUrl();
        location.href = sistema2+'buscar_producto.php?categorias='+$(this).val();
    })

    //activar campo para registrar cliente
    $('.btn_new_cliente').click(function(e){
        e.preventDefault();
        $('#nom_cliente').removeAttr('disabled');
        $('#ci_cliente').removeAttr('disabled');
        $('#tel1_cliente').removeAttr('disabled');
        $('#dir_cliente').removeAttr('disabled');
        $('#tel2_cliente').removeAttr('disabled');
        $('#cel1_cliente').removeAttr('disabled');
        $('#cel2_cliente').removeAttr('disabled');
        $('#ref1_cliente').removeAttr('disabled');
        $('#ref2_cliente').removeAttr('disabled');
        $('#ref3_cliente').removeAttr('disabled');

        $('#div_registro_cliente').slideDown();
    });

    //activar campo para registrar proveedor
    $('.btn_new_proveedor').click(function(e){
        e.preventDefault();
        $('#nom_proveedor').removeAttr('disabled');
        $('#con_proveedor').removeAttr('disabled');
        $('#tel_proveedor').removeAttr('disabled');
        $('#dir_proveedor').removeAttr('disabled');

        $('#div_registro_proveedor').slideDown();
    });
    //*******FIN******* activar campo para registrar proveedor

    //buscar cliente
    $('#ci_cliente').keyup(function(e){
        e.preventDefault();
        var cl = $(this).val();
        var action = 'searchCliente';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,cliente:cl},

            success: function(response){
                //console.log(response);
                if(response == 0){
                    $('#idCliente').val('');
                    $('#nom_cliente').val('');
                    $('#dir_cliente').val('');
                    $('#cel1_cliente').val('');
                    $('#cel2_cliente').val('');
                    $('#tel1_cliente').val('');
                    $('#tel2_cliente').val('');
                    $('#ref1_cliente').val('');
                    $('#ref2_cliente').val('');
                    $('#ref3_cliente').val('');
                    //mostrar boton de guardar
                    $('.btn_new_cliente').slideDown();
                }else{
                    var data = $.parseJSON(response);

                    $('#idCliente').val(data.idCliente);
                    //$('#ci_cliente').val(data.ci_ruc);
                    $('#nom_cliente').val(data.nombre);
                    $('#tel1_cliente').val(data.telefono1);
                    $('#dir_cliente').val(data.direccion);
                    $('#tel2_cliente').val(data.telefono2);
                    $('#cel1_cliente').val(data.celular1);
                    $('#cel2_cliente').val(data.celular2);
                    $('#ref1_cliente').val(data.referencia1);
                    $('#ref2_cliente').val(data.referencia2);
                    $('#ref3_cliente').val(data.referencia3);

                    //ocultar boton agregar
                    $('.btn_new_cliente').slideUp();

                    //bloqueo de campos /desabilitar
                    $('#nom_cliente').attr('disabled','disabled');
                    $('#tel1_cliente').attr('disabled','disabled');
                    $('#dir_cliente').attr('disabled','disabled');
                    $('#tel2_cliente').attr('disabled','disabled');
                    $('#cel1_cliente').attr('disabled','disabled');
                    $('#cel2_cliente').attr('disabled','disabled');
                    $('#ref1_cliente').attr('disabled','disabled');
                    $('#ref2_cliente').attr('disabled','disabled');
                    $('#ref3_cliente').attr('disabled','disabled');
                    
                    //ocultar boton guardar
                    $('#div_registro_cliente').slideUp();
                }
            },
            error: function(error){
            }
        });
    });

    // ******* BUSCAR PRODUCTO *******
    $('#buscar').keyup(function(e){
        e.preventDefault();
        var action = 'searchProduct';
        var buscar = $(this).val();

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,buscar:buscar},

            success: function(response){
                if(response == 0){
                    console.log(response);
                    $('#referencia').val('');
                    $('#producto').val('');
                    $('#proveedor').val('');
                    $('#marca').val('');
                    $('#categoria').val('');
                    $('#precioCosto').val('');
                    $('#existencia').val('');
                    $('#cuota').val('');
                    $('#total').val('');

                    $('#cuota3').val('');
                    $('#cuota6').val('');
                    $('#cuota9').val('');
                    $('#cuota12').val('');
                }else{
                    var data = $.parseJSON(response);
                    $('#referencia').val(data.referencia);
                    console.log(response);
                    $('#producto').val(data.nombreProducto);
                    $('#proveedor').val(data.nombreProveedor);
                    $('#marca').val(data.nombreMarca);
                    $('#categoria').val(data.nombreCategoria);
                    $('#precioCosto').val(data.precioCosto);
                    $('#existencia').val(data.existencia);
                    $('#cuota3').val(numberWithCommas(data.precio20));
                    $('#cuota6').val(numberWithCommas(data.precio45));
                    $('#cuota9').val(numberWithCommas(data.precio70));
                    $('#cuota12').val(numberWithCommas(data.precio90));
                    //funcion separador de miles.
                    function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
                }
            },
            error: function(error){
            }
        });
    });
    +
    // ******* FIN BUSCAR PRODUCTO *******

     //*******INICIO*******buscar proveedor
     $('#bnom_proveedor').keyup(function(e){
        e.preventDefault();
        var cl = $(this).val();
        var action = 'searchProveedor';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,proveedor:cl},

            success: function(response){
                //console.log(response);
                if(response == 0){
                    $('#nom_proveedor').val('');
                    $('#con_proveedor').val('');
                    $('#tel_proveedor').val('');
                    $('#dir_proveedor').val('');
                    
                    //mostrar boton de guardar
                    $('.btn_new_proveedor').slideDown();
                }else{
                    var data = $.parseJSON(response);
                    console.log(data);

                    $('#idProveedor').val(data.idProveedor);
                    $('#nom_proveedor').val(data.nombre);
                    $('#con_proveedor').val(data.contacto);
                    $('#tel_proveedor').val(data.telefono);
                    $('#dir_proveedor').val(data.direccion);

                    //ocultar boton agregar
                    $('.btn_new_proveedor').slideUp();

                    //bloqueo de campos /desabilitar
                    $('#nom_proveedor').attr('disabled','disabled');
                    $('#con_proveedor').attr('disabled','disabled');
                    $('#tel_proveedor').attr('disabled','disabled');
                    $('#dir_proveedor').attr('disabled','disabled');

                    //ocultar boton guardar
                    $('#div_registro_proveedor').slideUp();
                }
            },
            error: function(error){
            }
        });
    });
    //*******FIN******* buscar proveedor

    // //CREAR cliente - venta
    $('#form_new_cliente_venta').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_new_cliente_venta').serialize(),

            success: function(response){
                if(response != 'error'){
                    //agregar id al input hidden
                    $('#idCliente').val(response);
                    //bloqueo de campos
                    $('#nom_cliente').attr('disabled','disabled');
                    $('#tel1_cliente').attr('disabled','disabled');
                    $('#dir_cliente').attr('disabled','disabled');
                    $('#tel2_cliente').attr('disabled','disabled');
                    $('#cel1_cliente').attr('disabled','disabled');
                    $('#cel2_cliente').attr('disabled','disabled');
                    $('#ref1_cliente').attr('disabled','disabled');
                    $('#ref2_cliente').attr('disabled','disabled');
                    $('#ref3_cliente').attr('disabled','disabled');
                    //oculta boton agregar
                    $('.btn_new_cliente').slideUp();
                    //ocultar boton agregar
                    $('#div_registro_cliente').slideUp();

                }
            },
            error: function(error){

            }
        });
    });

    //*******INICIO******* CREAR proveedor - COMPRA
    $('#form_new_proveedor_compra').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_new_proveedor_compra').serialize(),

            success: function(response){
                if(response != 'error'){
                    //agregar id al input hidden
                    $('#idProveedor').val(response);
                    //bloqueo de campos
                    $('#nom_proveedor').attr('disabled','disabled');
                    $('#con_proveedor').attr('disabled','disabled');
                    $('#tel_proveedor').attr('disabled','disabled');
                    $('#dir_proveedor').attr('disabled','disabled');
                    
                    //oculta boton agregar
                    $('.btn_new_proveedor').slideUp();
                    //ocultar boton agregar
                    $('#div_registro_proveedor').slideUp();

                }
            },
            error: function(error){

            }
        });
    });
     //*******FIN******* CREAR proveedor - COMPRA

    // buscar producto - venta
    $('#txt_ref_productoVenta').keyup(function(e){
        e.preventDefault();
        var producto = $(this).val();
        var listaPrecio = $('#listaPrecio').val();
        var action   = 'infoProductoRef';
        console.log(producto);
        if(producto != ''){
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action,producto:producto,listaPrecio:listaPrecio},
    
                success: function(response){
                    if(response != 'error'){
                        var info  = JSON.parse(response);
                        console.log(info);
                        $('#txt_cod_productoVenta').val(info.idProducto);
                        $('#txt_codigoBarra_productoVenta').val(info.codigoDeBarra);
                        $('#txt_desctipcion').html(info.nombre);
                        $('#txt_existencia').html(info.existencia);
                        $('#txt_cant_productoVenta').val(1);
                        $('#txt_precio').html(numberWithCommas(info.precioVenta));
                        $('#txt_precio_total').html(numberWithCommas(info.precioVenta));
                        $('#txt_porc_max_desc').val(info.porc_max_desc);
                        //Activar cantidad
                        $('#txt_cant_productoVenta').removeAttr('disabled');
                        $('#txt_porc_desc').removeAttr('disabled');

                        //mostrar boton agregar
                        $('#add_product_venta').slideDown();
                    }else{
                        $('#txt_descripcion').html('-');
                        $('#txt_codigoBarra_productoVenta').val('');
                        $('#txt_existencia').html('-');
                        $('#txt_cant_productoVenta').val('0');
                        $('#txt_precio').html('0');
                        $('#txt_precio_total').html('0');
                        $('#txt_porc_desc').val('0');

                        //bloquear cantidad
                        $('#txt_cant_productoVenta').attr('disabled','disabled');

                        //ocultar boton agregar
                        //$('#add_product_venta').slideUp();
                    }
                },
                error: function(error){
    
                }
            });
        }
        function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
    });
    // buscar producto por codigo de barra - venta
    $('#txt_codigoBarra_productoVenta').keyup(function(e){
        e.preventDefault();
        var producto = $(this).val();
        var listaPrecio = $('#listaPrecio').val();
        var action   = 'infoProductoCodigoBarra';
        console.log(producto);
        if(producto != ''){
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action,producto:producto,listaPrecio:listaPrecio},
    
                success: function(response){
                    if(response != 'error'){
                        var info  = JSON.parse(response);
                        console.log(info);
                        $('#txt_cod_productoVenta').val(info.idProducto);
                        $('#txt_ref_productoVenta').val(info.referencia);
                        $('#txt_desctipcion').html(info.nombre);
                        $('#txt_existencia').html(info.existencia);
                        $('#txt_cant_productoVenta').val(1);
                        $('#txt_precio').html(numberWithCommas(info.precioVenta));
                        $('#txt_precio_total').html(numberWithCommas(info.precioVenta));
                        $('#txt_porc_max_desc').val(info.porc_max_desc);
                        //Activar cantidad
                        $('#txt_cant_productoVenta').removeAttr('disabled');
                        $('#txt_porc_desc').removeAttr('disabled');

                        //mostrar boton agregar
                        $('#add_product_venta').slideDown();
                    }else{
                        $('#txt_desctipcion').html('-');
                        $('#txt_ref_productoVenta').val('');
                        $('#txt_existencia').html('-');
                        $('#txt_cant_productoVenta').val('0');
                        $('#txt_precio').html('0');
                        $('#txt_precio_total').html('0');
                        $('#txt_porc_desc').val('0');

                        //bloquear cantidad
                        $('#txt_cant_productoVenta').attr('disabled','disabled');

                        //ocultar boton agregar
                        //$('#add_product_venta').slideUp();
                    }
                },
                error: function(error){
    
                }
            });
        }
        function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
    });

    /*BUSCADOR DE PRODUCTOS POR NOMBRES*/
    // buscar producto - venta
    $('#txt_nombre_productoVenta').keyup(function(e){
        e.preventDefault();
        var producto = $(this).val();
        var listaPrecio = $('#listaPrecio').val();
        var action   = 'infoProductoNombre';
        if($(this).val().length < 1){
            $('#detalle_busqueda_nombre').html("RESULTADO DE BUSQUEDA");
        }
        // console.log(producto);
        if(producto != ''){
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action,producto:producto,listaPrecio:listaPrecio},
    
                success: function(response){
                    if(response != 'error'){
                        var info  = JSON.parse(response);
                        console.log(info);
                        $('#detalle_busqueda_nombre').html(info.detalle);
                        
                    }else{
                        $('#detalle_busqueda_nombre').html("NO SE ENCONTRO NINGUNA COINCIDENCIA EN NOMBRE");
                    }
                },
                error: function(error){
    
                }
            });
        }
        // function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
    });
    /*FIN BUSCADOR DE PRODUCROS POR NOMBRES*/

    /*BUSCADOR DE PRODUCTOS POR NOMBRES COMPRAS*/
    $('#txt_nombre_productoCompra').keyup(function (e) {
        e.preventDefault();
        var producto = $(this).val();
        var action = 'infoProductoNombre2';
        if ($(this).val().length < 1) {
            $('#detalle_busqueda_nombre').html("RESULTADO DE BUSQUEDA");
        }
        // console.log(producto);
        if (producto != '') {
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: { action: action, producto: producto },

                success: function (response) {
                    if (response != 'error') {
                        var info = JSON.parse(response);
                        console.log(info);
                        $('#detalle_busqueda_nombre').html(info.detalle);

                    } else {
                        $('#detalle_busqueda_nombre').html("NO SE ENCONTRO NINGUNA COINCIDENCIA EN NOMBRE");
                    }
                },
                error: function (error) {
                }
            });
        }
    });
    /***FIN*** - BUSCADOR DE PRODUCTOS POR NOMBRES COMPRAS*/
    
    /*BUSCADOR DE PRODUCTOS POR NOMBRES INVENTARIOS*/
    $('#txt_nombre_productoInventario').keyup(function (e) {
        e.preventDefault();
        var producto = $(this).val();
        var action = 'infoProductoNombreInventario';
        if (producto != '') {
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: { action: action, producto: producto },
                success: function (response) {
                    if (response != 'error') {
                        var info = JSON.parse(response);
                        console.log(info);
                        $('#detalle_busqueda_nombre').html(info.detalle);
                    } else {
                        $('#detalle_busqueda_nombre').html("NO SE ENCONTRO NINGUNA COINCIDENCIA...");
                    }
                },
                error: function (error) {
                }
            });
        }
    });
    /*FIN BUSCADOR DE PRODUCTOS INVENTARIOS*/


    /*BUSCADOR DE CLINETE POR NOMBRES*/
    // buscar producto - venta
    $('#txt_nombre_cliente').keyup(function (e) {
        e.preventDefault();
        var cliente = $(this).val();
        var action = 'infoCliente';
        if ($(this).val().length < 1) {
            $('#detalle_busqueda_cliente').html("RESULTADO DE BUSQUEDA");
        }
        // console.log(producto);
        if (cliente != '') {
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: { action: action, cliente: cliente },

                success: function (response) {
                    if(response != 'error'){
                        var info  = JSON.parse(response);
                        console.log(info);
                        $('#detalle_busqueda_cliente').html(info.detalle);
                        
                    }else{
                        $('#detalle_busqueda_cliente').html("NO SE ENCONTRO NINGUNA COINCIDENCIA EN NOMBRE");
                    }
                },
                error: function(error){
    
                }
            });
        }
        // function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
    });
    /*FIN BUSCADOR DE CLIENTE POR NOMBRES*/

    /*BUSCADOR DE PROVEEDOR POR NOMBRES - COMPRAS*/
    $('#txt_nombre_proveedor').keyup(function (e) {
        e.preventDefault();
        var proveedor = $(this).val();
        var action = 'infoProveedor';
        if ($(this).val().length < 1) {
            $('#detalle_busqueda_proveedor').html("RESULTADO DE BUSQUEDA");
        }
        if (proveedor != '') {
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: { action: action, proveedor: proveedor },

                success: function (response) {
                    if (response != 'error') {
                        var info = JSON.parse(response);
                        console.log(info);
                        $('#detalle_busqueda_proveedor').html(info.detalle);

                    } else {
                        $('#detalle_busqueda_proveedor').html("NO SE ENCONTRO NINGUNA COINCIDENCIA EN NOMBRE");
                    }
                },
                error: function (error) {
                }
            });
        }
    });
    /*FIN BUSCADOR DE PROVEEDOR POR NOMBRES*/


        // buscar producto - PRESUPUESTO
        $('#txt_ref_productoPresupuesto').keyup(function(e){
            e.preventDefault();
            var producto = $(this).val();
            var action   = 'infoProductoRefPresupuesto';
            console.log(producto);
            if(producto != ''){
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    async: true,
                    data: {action:action,producto:producto},
        
                    success: function(response){
                        if(response != 'error'){
                            var info  = JSON.parse(response);
                            console.log(info);
                            $('#txt_cod_productoVenta').val(info.idProducto);
                            $('#txt_desctipcion').html(info.nombre);
                            $('#txt_existencia').html(info.existencia);
                            $('#txt_cant_productoPresupuesto').val(1);
                            $('#txt_precio').html(numberWithCommas(info.precioVenta));
                            $('#txt_precio_total').html(numberWithCommas(info.precioVenta));
                            
    
                            //Activar cantidad
                            $('#txt_cant_productoPresupuesto').removeAttr('disabled');
    
                            //mostrar boton agregar
                            $('#add_product_presupuesto').slideDown();
                        }else{
                            $('#txt_descripcion').html('-');
                            $('#txt_existencia').html('-');
                            $('#txt_cant_productoPresupuesto').val('0');
                            $('#txt_precio').html('0');
                            $('#txt_precio_total').html('0');
    
                            //bloquear cantidad
                            $('#txt_cant_productoPresupuesto').attr('disabled','disabled');
    
                            //ocultar boton agregar
                            $('#add_product_presupuesto').slideUp();
                        }
                    },
                    error: function(error){
        
                    }
                });
            }
            function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
        });
        /*FIN BUSCAR PRODUCTO PRESUPUESTO*/

    //*******INICIO******* buscar producto - COMPRA
    $('#txt_ref_producto').keyup(function(e){
        e.preventDefault();
        var producto = $(this).val();
        var action   = 'infoProducto_ref';
        if(producto != ''){
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action,producto:producto},
    
                success: function(response){
                    if(response != 'error'){
                        var info  =JSON.parse(response);
                        $('#txt_cod_producto2').val(info.idProducto);
                        $('#txt_desctipcion').html(info.nombre);
                        $('#txt_existencia').html(info.existencia);
                        $('#txt_cant_producto2').val(1);
                        $('#txt_precio').html(numberWithCommas(info.precioCosto));
                        $('#txt_precio_total').html(numberWithCommas(info.precioCosto));

                        //Activar cantidad
                        $('#txt_cant_producto2').removeAttr('disabled');

                        //mostrar boton agregar
                        $('#add_product_compra').slideDown();
                    }else{
                        $('#txt_ref_producto').html('-');
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('-');
                        $('#txt_cant_producto2').val('0');
                        $('#txt_precio').html('0');
                        $('#txt_precio_total').html('0');

                        //bloquear cantidad
                        $('#txt_cant_producto2').attr('disabled','disabled');

                        //ocultar boton agregar
                        $('#add_product_compra').slideUp();
                    }
                },
                error: function(error){
    
                }
            });
            function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
        }
    });
     //*******FIN******* buscar producto - COMPRA

    //validar la cantidad del producto antes de agregar
    $('#txt_cant_productoVenta').keyup(function(e){
        e.preventDefault();
        var precio_total = $(this).val() * numberSinComas($('#txt_precio').html());
        //obtener del html la existencia
        var existencia = parseInt($('#txt_existencia').html());
        $('#txt_precio_total').html(numberWithCommas(precio_total));
       

        //ocultar el boton agregar si la cantidad es menor que uno
        if(($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia) ){
            // $('#add_product_venta').slideUp();
            $('#add_product_venta').slideDown();
        }else{
            $('#add_product_venta').slideDown();
        }

        function numberSinComas(number) { return number.toString().replace(/[.]/g, ''); }
        function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
    })

    //validar la cantidad del producto antes de agregar presupuesto
    $('#txt_cant_productoPresupuesto').keyup(function(e){
        e.preventDefault();
        var precio_total = $(this).val() * numberSinComas($('#txt_precio').html());
        //obtener del html la existencia
        var existencia = parseInt($('#txt_existencia').html());
        $('#txt_precio_total').html(numberWithCommas(precio_total));

        //ocultar el boton agregar si la cantidad es menor que uno
        if(($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia) ){
            $('#add_product_presupuesto').slideUp();
        }else{
            $('#add_product_presupuesto').slideDown();
        }

        function numberSinComas(number) { return number.toString().replace(/[.]/g, ''); }
        function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
    })

    //*******INICIO******* validar la cantidad del producto antes de agregar - COMPRAS
    $('#txt_cant_producto2').keyup(function(e){
        e.preventDefault();
        var precio_total = $(this).val() * numberSinComas($('#txt_precio').html());
        //obtener del html la existencia
        var existencia = parseInt($('#txt_existencia').html());
        $('#txt_precio_total').html(numberWithCommas(precio_total));

        //ocultar el boton agregar si la cantidad es menor que uno
        if( ( isNaN($(this).val()))){
            $('#add_product_compra').slideUp();
        }else{
            $('#add_product_compra').slideDown();
        }
        function numberSinComas(number) { return number.toString().replace(/[.]/g, ''); }
        function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
    })
    //*******FIN******* validar la cantidad del producto antes de agregar - COMPRAS

    $('#txt_porc_desc').keyup(function(e){
        e.preventDefault();
        var txt_descuento = parseInt($(this).val());
        if(txt_descuento > $('#txt_porc_max_desc').val()){
            $('#txt_precio_total').html($('#txt_precio').html());
            alert ("Has superado el descuento maximo de este producto");
        }else{
        var precio_total_linea = numberSinComas($('#txt_precio').html()) * $('#txt_cant_productoVenta').val();
        var descuento = $(this).val();
        var precio_total = precio_total_linea - ((precio_total_linea * descuento) / 100);
        //obtener del html la existencia
        var existencia = parseInt($('#txt_existencia').html());
        $('#txt_precio_total').html(numberWithCommas(precio_total));
    

        //ocultar el boton agregar si la cantidad es menor que uno
        // if(($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia) ){
        //     // $('#add_product_venta').slideUp();
        //     $('#add_product_venta').slideDown();
        // }else{
        //     $('#add_product_venta').slideDown();
        // }

        function numberSinComas(number) { return number.toString().replace(/[.]/g, ''); }
        function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
    }
    })



    //**********INICIO*******PARA HABILITAR Y DESABILITAR EL CAMPO DE CUOTAS */
    $('#tipo').click(function(e){
        var tipo = $('#tipo').val();
        if(tipo == 2){
            $('#cantCuotas').removeAttr('disabled');
            $('#tiempo').removeAttr('disabled');
            $('#entrega').removeAttr('disabled');
            $('#montoEntrega').removeAttr('disabled');
        }else{
            $('#cantCuotas').attr('disabled','disabled');
            $('#tiempo').attr('disabled','disabled');
            $('#entrega').attr('disabled','disabled');
            $('#montoEntrega').attr('disabled','disabled');
        }
        //console.log(tipo);
    })
    //**********INICIO*******PARA HABILITAR Y DESABILITAR EL CAMPO DE CUOTAS */

    //calculo para vuelto
    $('#efectivo').keyup(function(e){key
        e.preventDefault();
        alert('click  efectivo');
        var vuelto = $('#monto_a_cobrar').val() - $(this).val();
                    //obtener del html la existencia
                // var existencia = parseInt($('#txt_existencia').html());
        $('#vuelto').val(vuelto);
        console.log($(this).value);

                        //ocultar el boton agregar si la cantidad es menor que uno
                        //if(($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia) ){
                        //  $('#add_product_venta').slideUp();
                        //}else{
                        //  $('#add_product_venta').slideDown();
                        //}
    })

   

    //*******INICIO******* agregar producto al detalle - VENTA
    $('#add_product_venta').click(function(e){
        e.preventDefault();
        
        if($('#txt_cant_productoVenta').val() > 0){
            var idProducto      = $('#txt_cod_productoVenta').val();
            var cantidad        = $('#txt_cant_productoVenta').val();
            var porc_desc       = $('#txt_porc_desc').val();
            var total_linea     = numberSinComas($('#txt_precio_total').html());
            var listaPrecio     = $('#listaPrecio').val();
            var action          = 'addProductoDetalle';
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action,producto:idProducto,cantidad:cantidad,porc_desc:porc_desc,total_linea:total_linea,listaPrecio:listaPrecio},
    
                success: function(response){
                    if(response != 'error'){
                        
                        var info = JSON.parse(response);
                        if(info == 'negativo'){
                            alert("Ya no se puede agregar mas productos, FAVOR crear nueva venta.")
                        }else{
                            $('#detalle_venta').html(info.detalle);
                            $('#detalle_totales').html(info.totales);
                            $('#txt_codigoBarra_productoVenta').val('');
                            $('#txt_cod_productoVenta').val('');
                            $('#txt_ref_productoVenta').val('');
                            $('#txt_descripcion').html('-');
                            $('#txt_existencia').html('0');
                            $('#txt_cant_productoVenta').val('0');
                            $('#txt_precio').html('0');
                            $('#txt_precio_total').html('0');
    
                            //bloquear cantidad
                            $('#txt_cant_productoVenta').attr('disabled','disabled');
    
                            //ocultar el boton agregar
                            $('#add_product_venta').slideUp();
                        }
                        
                    }else{
                        console.log('no data venta');
                    }
                    viewProcesarVenta();
                },
                error: function(error){
    
                }
            });
            function numberSinComas(number) { return number.toString().replace(/[.]/g, ''); }
        }
    });

        //*******INICIO******* agregar producto al detalle - PRESUPUESTO
        $('#add_product_presupuesto').click(function(e){
            e.preventDefault();
            
            if($('#txt_cant_productoPresupuesto').val() > 0){
                var idProducto = $('#txt_cod_productoVenta').val();
                var cantidad   = $('#txt_cant_productoPresupuesto').val();
                var action     = 'addProductoDetallePresupuesto';

    
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    async: true,
                    data: {action:action,producto:idProducto,cantidad:cantidad},
        
                    success: function(response){
                        if(response != 'error'){
                            var info = JSON.parse(response);
                            $('#detalle_presupuesto').html(info.detalle);
                            $('#detalle_totales_presupuesto').html(info.totales);
    
                            $('#txt_cod_productoVenta').val('');
                            $('#txt_ref_productoPresupuesto').val('');
                            $('#txt_descripcion').html('-');
                            $('#txt_existencia').html('0');
                            $('#txt_cant_productoPresupuesto').val('0');
                            $('#txt_precio').html('0');
                            $('#txt_precio_total').html('0');
    
                            //bloquear cantidad
                            $('#txt_cant_productoPresupuesto').attr('disabled','disabled');
    
                            //ocultar el boton agregar
                            $('#add_product_presupuesto').slideUp();
                        }else{
                            console.log('no data venta');
                        }
                        viewProcesarPresupuesto();
                    },
                    error: function(error){
        
                    }
                });
    
            }
        });
        /*FIN AGREGAR PRODUCTO DETALLES PRESUPUESTO*/

    //*******INICIO******* agregar gasto al detalle - GASTO
    $('#add_gasto').click(function(e){
        e.preventDefault();
        
            var descripcion = $('#txt_descripcion').val();
            var monto   = numberSinComas($('#txt_monto').val());
            var action     = 'addGastoDetalles';

            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action,descripcion:descripcion,monto:monto},
    
                success: function(response){
                    console.log(response);
                    if(response != 'error'){
                        var info = JSON.parse(response);
                        $('#detalle_gasto').html(info.detalle);
                        $('#detalle_totales_gasto').html(info.totales);

                        $('#txt_descripcion').val('');
                        $('#txt_monto').val('');
                    }else{
                        console.log('no data gasto');
                    }
                    viewProcesarGasto();
                },
                error: function(error){
    
                }
            });
            function numberSinComas(number) { return number.toString().replace(/[.]/g, ''); }
    });

    //*******INICIO******* agregar producto al detalle COMPRA
    $('#add_product_compra').click(function(e){
        e.preventDefault();
        
        if($('#txt_cant_producto2').val() > 0){
            var idProducto = $('#txt_cod_producto2').val();
            var cantidad   = $('#txt_cant_producto2').val();
            var action     = 'addProductoDetalleCompra';
            console.log(idProducto);
            console.log(cantidad);
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                //dataType: 'json',
                async: true,
                data: {action:action,producto:idProducto,cantidad:cantidad},
    
                success: function(response){
                    console.log(response);
                    if(response != 'error'){
                        console.log(response);
                        var info2 = response;
                        console.log(info2);
                        var info = JSON.parse(response);
                        console.log('datos a mostrar' + info);
                        $('#detalle_ventaCompra').html(info.detalle);
                        $('#detalle_totalesCompra').html(info.totales);
                        
                        $('#txt_cod_producto2').val('');
                        $('#txt_ref_producto').val('');
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('0');
                        $('#txt_cant_producto').val('0');
                        $('#txt_precio').html('0');
                        $('#txt_precio_total').html('0');

                        //bloquear cantidad
                        $('#txt_cant_producto').attr('disabled','disabled');

                        //ocultar el boton agregar
                        $('#add_product_compra').slideUp();
                    }else{
                        console.log('no data agregar detalle compra');
                    }
                    viewProcesar();
                },
                error: function(error){
                }
            });
        }
    });
    //*******FIN******* agregar producto al detalle COMPRA




    //anular venta
    $('#btn_anular_venta').click(function(e){
        e.preventDefault();
        var rows = $('#detalle_venta tr').length;

        if(rows > 0){
            var action = 'anularVenta';
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action},
    
                success: function(response){
                    if(response != 'error'){
                        location.reload();
                    }
                },
                error: function(error){
    
                }
            });

        }
    });

        //anular presupuesto
        $('#btn_anular_presupuesto').click(function(e){
            e.preventDefault();
            var rows = $('#detalle_presupuesto tr').length;
    
            if(rows > 0){
                // var action = 'anularVenta';
                var action = 'anularPresupuesto';
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    async: true,
                    data: {action:action},
        
                    success: function(response){
                        if(response != 'error'){
                            location.reload();
                        }
                    },
                    error: function(error){
        
                    }
                });
    
            }
        });
        /*FIN ANULAR PRESUPUESTO*/

        //anular GASTO
        $('#btn_anular_gasto').click(function(e){
            e.preventDefault();
            var rows = $('#detalle_gasto tr').length;
    
            if(rows > 0){
                var action = 'anularGasto';
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    async: true,
                    data: {action:action},
        
                    success: function(response){
                        if(response != 'error'){
                            location.reload();
                        }
                    },
                    error: function(error){
        
                    }
                });
    
            }
        });

    //*******INICIO******* anular COMPRA
    $('#btn_anular_compra').click(function(e){
        e.preventDefault();
        var rows = $('#detalle_ventaCompra tr').length;

        if(rows > 0){
            var action = 'anularCompra';
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action},
    
                success: function(response){
                    if(response != 'error'){
                        location.reload();
                    }
                },
                error: function(error){
    
                }
            });
        }
    });
    //*******FIN******* anular COMPRA


    //procesar venta
    $('#btn_facturar_venta').click(function(e){
        e.preventDefault();
        var rows = $('#detalle_venta tr').length;

        if(rows > 0){
            var idCliente = $('#idCliente').val();
            var tipo = $('#tipo').val();
            var tiempo = $('#tiempo').val();
            var cantCuotas = $('#cantCuotas').val();
            var noFacturaLegal = $('#noFacturaLegal').val(); // ESTIRA DATOS DEL CAMPO NO. FACTURA LEGAL
            var entrega = $('#entrega').val();
            var montoEntrega = numberSinComas($('#montoEntrega').val());
            var idObra = $('#obra').val();

            if(tiempo == 1){
                var action = 'procesarVenta';
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    async: true,
                    data: {action:action,idCliente:idCliente,tipo:tipo,cantCuotas:cantCuotas,noFacturaLegal:noFacturaLegal,entrega:entrega,montoEntrega:montoEntrega,idObra:idObra},
        
                    success: function(response){
                        if(response != 'error'){
                             var info = JSON.parse(response);

                            //LIMPIAR CAMPOS DE PROVEEDOR
                            $('#ci_cliente').val('');
                            $('#nom_cliente').val('');
                            $('#dir_cliente').val('');
                            $('#cel1_cliente').val('');
                            $('#cel2_cliente').val('');
                            $('#tel1_cliente').val('');
                            $('#tel2_cliente').val('');
                            $('#ref1_cliente').val('');
                            $('#ref2_cliente').val('');
                            $('#ref3_cliente').val('');
                            $('#noFacturaLegal').val('');
                            $('#cantCuotas').val(1);
                            // generarPDFVenta(info.idCliente,info.idVenta);
                            generarPDFticket(info.idCliente,info.idVenta);
                            location.reload();

                        }else{
                            console.log('no data function');
                        }
                    },
                    error: function(error){
        
                    }
                });
            }else{
                var action = 'procesarVentaSemanal';
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    async: true,
                    data: {action:action,idCliente:idCliente,tipo:tipo,cantCuotas:cantCuotas,noFacturaLegal:noFacturaLegal,entrega:entrega,montoEntrega:montoEntrega,idObra:idObra},
        
                    success: function(response){
                        //console.log(response);
                        if(response != 'error'){
                             var info = JSON.parse(response);
                             //console.log(info);
                            //generarPDF(info.idCliente,info.idVenta);
                            location.reload();
                        }else{
                            console.log('no data function credito semanal');
                        }
                    },
                    error: function(error){
        
                    }
                });
            }
        }
        function numberSinComas(number) { return number.toString().replace(/[.]/g, ''); }
    });

    //INICIO PROCESAR PRESUPUESTO
    $('#btn_facturar_presupuesto').click(function(e){
        e.preventDefault();
        var rows = $('#detalle_presupuesto tr').length;

        if(rows > 0){
            var idCliente = $('#idCliente').val();
            var tipo = $('#tipo').val();
            // var tiempo = $('#tiempo').val();
            // var cantCuotas = $('#cantCuotas').val();
            // var noFacturaLegal = $('#noFacturaLegal').val(); // ESTIRA DATOS DEL CAMPO NO. FACTURA LEGAL
            // var noFacturaLegal = 1;
            // var entrega = $('#entrega').val();
            // var montoEntrega = numberSinComas($('#montoEntrega').val());

            // if(tiempo == 1){
                // var action = 'procesarVenta';
                var action = 'procesarPresupuesto';

                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    async: true,
                    // data: {action:action,idCliente:idCliente,tipo:tipo,cantCuotas:cantCuotas,noFacturaLegal:noFacturaLegal,entrega:entrega,montoEntrega:montoEntrega},
                    data: {action:action,idCliente:idCliente,tipo:tipo},
        
                    success: function(response){
                        if(response != 'error'){
                             var info = JSON.parse(response);
                            console.log(info);
                            //LIMPIAR CAMPOS DE PROVEEDOR
                            $('#ci_cliente').val('');
                            $('#nom_cliente').val('');
                            $('#dir_cliente').val('');
                            $('#cel1_cliente').val('');
                            $('#cel2_cliente').val('');
                            $('#tel1_cliente').val('');
                            $('#tel2_cliente').val('');
                            $('#ref1_cliente').val('');
                            $('#ref2_cliente').val('');
                            $('#ref3_cliente').val('');
                            $('#noFacturaLegal').val('');
                            $('#cantCuotas').val(1);
                            
                            // generarPDFVenta(info.idCliente,info.idVenta);
                            generarPDFpresupuesto(info.idCliente,info.idPresupuesto);
                            location.reload();

                        }else{
                            console.log('no data function PRESUPUESTO');
                        }
                    },
                    error: function(error){
        
                    }
                });
            // }
        }
        function numberSinComas(number) { return number.toString().replace(/[.]/g, ''); }
    });
    /*FIN PROCESAR PRESUPUSTO*/

     //procesar GASTO
     $('#btn_procesar_gasto').click(function(e){
        e.preventDefault();
        var rows = $('#detalle_gasto tr').length;
        var tipoDocumento = $('#tipoDocumento').val();
        var noDocumento = $('#noDocumento').val();
        var fecha = $('#fecha').val();
        if(fecha === ''){
            alert(new Date());
        }
        console.log(fecha);
        if(noDocumento === '' || isNaN(noDocumento)){
            alert("Favor completar el No. de Documentos y/o verifique que sea solo números");
        }else{
            if(rows > 0){
                var action = 'procesarGasto';
                console.log(tipoDocumento+noDocumento+action);
                    $.ajax({
                        url: 'ajax.php',
                        type: 'POST',
                        async: true,
                        data: {action:action,tipoDocumento:tipoDocumento,noDocumento:noDocumento,fecha:fecha},
            
                        success: function(response){
                            if(response != 'error'){
                                 var info = JSON.parse(response);
                                 //console.log(info);
                                //generarPDF(info.idCliente,info.idVenta);
                                $('#noDocumento').val('');
                                location.reload();
                            }else{
                                console.log('no data function - btn procesar gasto');
                            }
                        },
                        error: function(error){
                        }
                    });
            }
        }
        
    });

    //*******INICIO******* procesar COMPRA
    $('#btn_facturar_compra').click(function(e){
        e.preventDefault();
        var rows = $('#detalle_ventaCompra tr').length;

        if(rows > 0){
            var action = 'procesarCompra';
            var idProveedor = $('#idProveedor').val();
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action,idProveedor:idProveedor},
    
                success: function(response){
                    //console.log(response);
                    if(response != 'error'){
                        var info = JSON.parse(response);
                        //console.log(info);
                        //generarPDF(info.idCliente,info.idVenta);
                        
                        //LIMPIAR CAMPOS DE PROVEEDOR
                        $('#bnom_proveedor').val('');
                        $('#nom_proveedor').val('');
                        $('#con_proveedor').val('');
                        $('#tel_proveedor').val('');
                        $('#dir_proveedor').val('');
                        // generarPDFCompra(info.idProveedor,info.idCompra);
                        location.reload();
                    }else{
                        console.log('no data');
                    }
                },
                error: function(error){
                }
            });
        }
    });
    //*******FIN******* procesar COMPRA

    //********INICIO******* para anular compra
// $('.anular_factura').click(function(e){
//     e.preventDefault();
//     var nofactura = $(this).attr('fac');
//     var action = 'infoFactura';
//     $.ajax({
//         url: 'ajax.php',
//         type: 'POST',
//         async: true,
//         data: {action:action,nofactura:nofactura},

//         success: function(response){
//             if(response != 'error'){
//                 var info = JSON.parse(response);
//                 console.log(info);


//             }
            
//         },
//         error: function(error){
//             console.log();
//         }
//     });
// });

//*******INICIO******* REIMPRIMIR FACTURA
$('.imprimir_factura_compra').click(function(e){
    e.preventDefault();
    
    var nofactura = $(this).attr('fac');
    var idProveedor = $(this).attr('prov');
    console.log('imprimir factura compra no. ' . nofactura);

    generarPDFCompra(idProveedor,nofactura);
    // var action = 'infoFactura'
    
    // $.ajax({
    //     url: 'ajax.php',
    //     type: 'POST',
    //     async: true,
    //     data: {action:action,nofactura:nofactura},

    //     success: function(response){
    //         //console.log(response);
    //         if(response != 'error'){
    //             var info = JSON.parse(response);
    //             console.log(info);
               

    //                 $('.bodyModal').html('<form action="" method="post" name="form_anular_factura" id="form_anular_factura" onsubmit="event.preventDefault(); anularFactura();">'+
    //             '<h1><i class="fas fa-cubes" style="font-size: 45px;"></i> <br> Anular Compra</h1>'+
    //             '<p>¿Realmente desea anular la compra?</p>'+

    //             '<p><strong>No. '+info.idCompra+'</strong></p>'+
    //             '<p><strong>Monto. Gs.'+info.totalCompra+'</strong></p>'+
    //             '<p><strong>Fecha. '+info.fecha+'</strong></p>'+
    //             '<input type="hidden" name="action" value="anularFactura">'+
    //             '<input type="hidden" name="no_factura" id="no_factura" value="'+info.idCompra+'">'+

               
    //             '<div class="alert alertAddProduct"></div>'+
    //             '<button type="submit" class="btn_new"><i class="fas fa-plus"></i> Anular</button>'+
    //             '<a href="#" class="btn_ok closeModal" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+

    //         '</form>' );
    //         }
    //     },
        
    //     error: function(error){
    //         console.log(error);
    //     }
    // });
    // $('.modal').fadeIn();
});
//*******FIN REIMPRIMIR FACTURA COMPRA *******

//*******INICIO******* REIMPRIMIR PRESUPUESTO
$('.imprimir_presupuesto').click(function(e){
    e.preventDefault();
    
    var noPresupuesto = $(this).attr('id_Presupuesto');
    var idCliente = $(this).attr('id_Cliente');
    console.log('imprimir factura compra no. ' . nofactura);

    generarPDFpresupuesto(idCliente,noPresupuesto);
    
});
//*******FIN REIMPRIMIR PRESUPUESTO *******

//*******INICIO******* REIMPRIMIR PRESUPUESTO
$('.imprimir_factura_venta').click(function(e){
    e.preventDefault();
    
    var noVenta = $(this).attr('f');
    var idCliente = $(this).attr('cl');
    // console.log('imprimir factura compra no. ' . nofactura);

    generarPDFVenta(idCliente,noVenta);
    
});
//*******FIN REIMPRIMIR PRESUPUESTO *******

//*******INICIO******* REIMPRIMIR TICKET
$('.imprimir_ticket_venta').click(function(e){
    e.preventDefault();
    
    var noVenta2 = $(this).attr('f2');
    var idCliente2 = $(this).attr('cl2');
    // console.log('imprimir factura compra no. ' . noVenta2);

    generarPDFticket(idCliente2,noVenta2);
    
});
//*******FIN REIMPRIMIR TICKET *******


//*******INICIO******* Modal cancelar compra
$('.anular_factura').click(function(e){
    e.preventDefault();
    
    var nofactura = $(this).attr('fac');
    var action = 'infoFactura'
    
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,nofactura:nofactura},

        success: function(response){
            //console.log(response);
            if(response != 'error'){
                var info = JSON.parse(response);
                console.log(info);
               

                    $('.bodyModal').html('<form action="" method="post" name="form_anular_factura" id="form_anular_factura" onsubmit="event.preventDefault(); anularFactura();">'+
                '<h1><i class="fas fa-cubes" style="font-size: 45px;"></i> <br> Anular Compra</h1>'+
                '<p>¿Realmente desea anular la compra?</p>'+

                '<p><strong>No. '+info.idCompra+'</strong></p>'+
                '<p><strong>Monto. Gs.'+info.totalCompra+'</strong></p>'+
                '<p><strong>Fecha. '+info.fecha+'</strong></p>'+
                '<input type="hidden" name="action" value="anularFactura">'+
                '<input type="hidden" name="no_factura" id="no_factura" value="'+info.idCompra+'">'+

               
                '<div class="alert alertAddProduct"></div>'+
                '<button type="submit" class="btn_new"><i class="fas fa-plus"></i> Anular</button>'+
                '<a href="#" class="btn_ok closeModal" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+

            '</form>' );
            }
        },
        
        error: function(error){
            console.log(error);
        }
    });
    $('.modal').fadeIn();
});
//*******FIN******* Modal cancelar compra

//*******INICIO******* Modal cancelar venta
$('.anular_facturaVenta').click(function(e){
    e.preventDefault();
    
    var nofactura = $(this).attr('facventa');
    var action = 'infoFacturaVenta'
    
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,nofactura:nofactura},

        success: function(response){
            //console.log(response);
            if(response != 'error'){
                var info = JSON.parse(response);
                console.log(info);
               

                    $('.bodyModal').html('<form action="" method="post" name="form_anular_facturaVenta" id="form_anular_facturaVenta" onsubmit="event.preventDefault(); anularFacturaVenta();">'+
                '<h1><i class="fas fa-cubes" style="font-size: 45px;"></i> <br> Anular Venta</h1>'+
                '<p>¿Realmente desea anular la Venta?</p>'+

                '<p><strong>No. '+info.idVenta+'</strong></p>'+
                '<p><strong>Monto. Gs.'+info.totalVenta+'</strong></p>'+
                '<p><strong>Fecha. '+info.fecha+'</strong></p>'+
                '<input type="hidden" name="action" value="anularFacturaVenta">'+
                '<input type="hidden" name="no_factura" id="no_factura" value="'+info.idVenta+'">'+

               
                '<div class="alert alertAddProduct"></div>'+
                '<button type="submit" class="btn_new"><i class="fas fa-plus"></i> Anular</button>'+
                '<a href="#" class="btn_ok closeModal" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+

            '</form>' );
            }
        },
        
        error: function(error){
            console.log(error);
        }
    });
    $('.modal').fadeIn();
});
//*******FIN******* Modal cancelar venta


$('.view_factura').click(function(e){
    e.preventDefault();
    var idCliente = $(this).attr('cl');
    generarEstadoCuentaPDF(idCliente);
});

$('.view_estado_cuenta').click(function(e){
    e.preventDefault();
    var idCliente = $(this).attr('cl');
    generarEstadoCuenta2PDF(idCliente);
});
//para generar el extrato del cliente sin intereses
$('.view_estado_cuentaSinInteres').click(function(e){
    e.preventDefault();
    var idCliente = $(this).attr('cl');
    generarEstadoCuentaSinInteresPDF(idCliente);
});

}); //end ready

//*******INICIO******* anular factura compra
function anularFactura(){
    var noFactura = $('#no_factura').val();
    var action = 'anularFactura';

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,noFactura:noFactura},

        success: function(response){
            // console.log(response);
            if(response == 'error'){
                $('.alertAddProduct').html('<p style="color: red;"> Error al anular la factura</p>');
            }else{
                $('#row_'+noFactura+' .estado').html('<span class="anulada">Anulada</span>');
                $('#form_anular_factura .btn_new').remove();
                $('#row_'+noFactura+' .div_factura').html('<button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>');
                $('.alertAddProduct').html('<p>Factura Anulada</p>');
            }
        },
        error: function(error){
        }
    });
}
//*******FIN******* anular factura compra

//*******INICIO******* anular factura venta
function anularFacturaVenta(){
    var noFactura = $('#no_factura').val();
    var action = 'anularFacturaVenta';

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,noFactura:noFactura},

        success: function(response){
            // console.log(response);
            if(response == 'error'){
                $('.alertAddProduct').html('<p style="color: red;"> Error al anular la factura</p>');
            }else{
                $('#row_'+noFactura+' .estado').html('<span class="anulada">Anulada</span>');
                $('#form_anular_facturaVenta .btn_new').remove();
                $('#row_'+noFactura+' .div_factura').html('<button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>');
                $('.alertAddProduct').html('<p>Factura Anulada</p>');
            }
        },
        error: function(error){
        }
    });
}
//*******FIN******* anular factura venta





//Enviar datos desde modal addproduct
// function sendDataProduct(){
//     $('.alertAddProduct').html('');

//     $.ajax({
//         url: 'ajax.php',
//         type: 'POST',
//         async: true,
//         data: $('#form_add_product').serialize(),

//         success: function(response){
//             console.log(response);
//         },
       
//         error: function(error){
//             console.log(error);
//         }
//     });

// }

//Enviar datos desde modal cobrar
function sendDataCobrar(){
    $('.alertAddProduct').html('');
    var id_cuota = $('#id_cuota').val();        // id de la cuota o numero de la cuota
    var action = 'cobrar_cuota';                // nombre de la accion a ejecutarse en el ajax
    var monto_recibido = $('#efectivo').val();  // monto abonado
    var mora = $('#mora').val();                // el interes por mora
    var saldo = $('#saldo').val();              // la diferencia entre el monto a cobrar y el efectivo (monto abonado)
    var monto = $('#monto_a_cobrar').val();     // Monto total a pagar
    var pago = $('#montoRecibido').val();       //
    var montoSaldo = $('#montoSaldo').val();    //
   // var exonerar_interes = $('#exin').val();
    var montoCuota = $('#montoCuota').val();
    var id_venta   =      $('#id_venta').val();
    var id_cliente =      $('#id_cliente').val();

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,id_cuota:id_cuota,monto_recibido:monto_recibido,mora:mora,saldo:saldo,pago:pago,montoSaldo:montoSaldo,monto:monto,montoCuota:montoCuota},
       
        success: function(response){
            if(response == 'error'){
                $('.alertAddProduct').html('<p class="color: red;">Error al PAGAR</p>');
            }else{
                var info = (response);
                console.log(info);
                $('.row_'+info.id+' .celstatus').html(info.status);
                $('#num_cuota').val('');
                $('#monto_a_cobrar').val('');
                $('#efectivo').val('');
                $('#vuelto').val('');
                $('.alertAddProduct').html('<p>PROCESADO CORRECTAMENTE</p>');
                generarPDFVenta(id_cliente,id_venta);
            }
            if(response == 'errorD'){
                $('.alertAddProduct').html('<p class="color: red;">Error al PAGAR dddd</p>');
            }    
        },
       
        error: function(error){
            console.log(error);
        }
    });
}


//*******PARA CERRAR EL MODAL */
function closeModal(){
    $('.alertAddProduct').html('');
    $('#txtCantidad').val('');
    $('#txtPrecio').val('');
    $('.modal').fadeOut();
}


//para generar factura
function generarPDF(cliente,factura){
    var ancho = 1200;
    var alto = 1000;
    //calcular posicion x,y para centrar la ventana
    var x = parseInt((window.screen.width/2) - (ancho / 2));
    var y = parseInt((window.screen.height/2) - (alto / 2));

    $url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
    window.open($url,"Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=no,menubar=no");
        // var nueva_ventana = window.open('prueba_opera.htm','prueba','width=600px,height=450px,top=50px,left=50px,menubar=yes,resizable=yes,location=yes,scrollbars=yes,status=yes,toolbar=yes');
    //console.log($url);
}

//para generar factura
function generarEstadoCuentaPDF(cliente){
    var ancho = 1200;
    var alto = 1000;
    //calcular posicion x,y para centrar la ventana
    var x = parseInt((window.screen.width/2) - (ancho / 2));
    var y = parseInt((window.screen.height/2) - (alto / 2));

    $url = 'factura/estadoCuenta.php?cl='+cliente;
    window.open($url,"Estado de Cuenta del Cliente","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=no,menubar=no");
}

//para generar el pdf de estado de cuenta solo lo que faltan pagar
function generarEstadoCuenta2PDF(cliente){
    var ancho = 1200;
    var alto = 1000;
    //calcular posicion x,y para centrar la ventana
    var x = parseInt((window.screen.width/2) - (ancho / 2));
    var y = parseInt((window.screen.height/2) - (alto / 2));

    $url = 'factura/estadoCuenta2.php?cl='+cliente;
    window.open($url,"Estado de Cuenta del Cliente","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=no,menubar=no");
}
//INICIO: para generar el pdf de estado de cuenta sin pagar y sin interes.
function generarEstadoCuentaSinInteresPDF(cliente){
    var ancho = 1200;
    var alto = 1000;
    //calcular posicion x,y para centrar la ventana
    var x = parseInt((window.screen.width/2) - (ancho / 2));
    var y = parseInt((window.screen.height/2) - (alto / 2));

    $url = 'factura/estadoCuentaSinInteres.php?cl='+cliente;
    window.open($url,"Estado de Cuenta del Cliente Sin Interes","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=no,menubar=no");
}
//FIN: para generar el pdf de estado de cuenta sin pagar y sin interes.
//para generar ticket de venta
function generarPDFticket(cliente,factura){
    var ancho = 1200;
    var alto = 1000;
    //calcular posicion x,y para centrar la ventana
    var x = parseInt((window.screen.width/2) - (ancho / 2));
    var y = parseInt((window.screen.height/2) - (alto / 2));

    // $url = 'factura/genera_nota_pedido_24x14cm.php?cl='+cliente+'&f='+factura; //ferreteriaFiorella
    $url = 'factura/generaTicket.php?cl='+cliente+'&f='+factura; //electropama
    window.open($url,"Ticket","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=no,menubar=no");
        // var nueva_ventana = window.open('prueba_opera.htm','prueba','width=600px,height=450px,top=50px,left=50px,menubar=yes,resizable=yes,location=yes,scrollbars=yes,status=yes,toolbar=yes');
    //console.log($url);
}

//para generar ticket de venta
function generarPDFpresupuesto(cliente,factura){
    var ancho = 1200;
    var alto = 1000;
    //calcular posicion x,y para centrar la ventana
    var x = parseInt((window.screen.width/2) - (ancho / 2));
    var y = parseInt((window.screen.height/2) - (alto / 2));

    $url = 'factura/generaPresupuesto.php?cl='+cliente+'&f='+factura;
    window.open($url,"Ticket","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=no,menubar=no");
        // var nueva_ventana = window.open('prueba_opera.htm','prueba','width=600px,height=450px,top=50px,left=50px,menubar=yes,resizable=yes,location=yes,scrollbars=yes,status=yes,toolbar=yes');
    //console.log($url);
}

//para generar factura de venta
function generarPDFVenta(cliente,factura){
    var ancho = 1200;
    var alto = 1000;
    //calcular posicion x,y para centrar la ventana
    var x = parseInt((window.screen.width/2) - (ancho / 2));
    var y = parseInt((window.screen.height/2) - (alto / 2));

    $url = 'factura/generaFacturaVenta.php?cl='+cliente+'&f='+factura;
    window.open($url,"Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=no,menubar=no");
        // var nueva_ventana = window.open('prueba_opera.htm','prueba','width=600px,height=450px,top=50px,left=50px,menubar=yes,resizable=yes,location=yes,scrollbars=yes,status=yes,toolbar=yes');
    //console.log($url);
}

//para generar factura de compra
function generarPDFCompra(proveedor,factura){
    var ancho = 1200;
    var alto = 1000;
    //calcular posicion x,y para centrar la ventana
    var x = parseInt((window.screen.width/2) - (ancho / 2));
    var y = parseInt((window.screen.height/2) - (alto / 2));

    $url = 'factura/generaFacturaCompra.php?pr='+proveedor+'&f='+factura;
    window.open($url,"Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=no,menubar=no");
        // var nueva_ventana = window.open('prueba_opera.htm','prueba','width=600px,height=450px,top=50px,left=50px,menubar=yes,resizable=yes,location=yes,scrollbars=yes,status=yes,toolbar=yes');
    //console.log($url);
    console.log("estoy dentro de la funcion generar pdf compra" + proveedor);
}


/*
    Codigo para reimprimir la factura de compra.
    Para generar la factura de compra en lista de compras procesadas.
*/
function generarPDFCompra(proveedor,factura){
    var ancho = 1200;
    var alto = 1000;
    //calcular posicion x,y para centrar la ventana
    var x = parseInt((window.screen.width/2) - (ancho / 2));
    var y = parseInt((window.screen.height/2) - (alto / 2));

    $url = 'factura/generaFacturaCompra.php?pr='+proveedor+'&f='+factura;
    window.open($url,"Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=no,menubar=no");
        // var nueva_ventana = window.open('prueba_opera.htm','prueba','width=600px,height=450px,top=50px,left=50px,menubar=yes,resizable=yes,location=yes,scrollbars=yes,status=yes,toolbar=yes');
    //console.log($url);
    console.log("estoy dentro de la funcion generar pdf compra" + proveedor);
}

//para eliminar items de linea detalles venta temp
function del_producto_detalle(id){
    var action = 'del_producto_detalle';
    var id_detalle = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action, id_detalle:id_detalle},

        success: function(response){
            if(response != 'error'){
                var info  = JSON.parse(response);
                        $('#detalle_venta').html(info.detalle);
                        $('#detalle_totales').html(info.totales);

                        $('#txt_cod_producto').val('');
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('0');
                        $('#txt_cant_producto').val('0');
                        $('#txt_precio').html('0');
                        $('#txt_precio_total').html('0');

                        //bloquear cantidad
                        $('#txt_cant_producto').attr('disabled','disabled');

                        //ocultar el boton agregar
                        $('#add_product_venta').slideUp();
            }else{
                $('#detalle_venta').html('');
                $('#detalle_totales').html('');

            }
            viewProcesarVenta();
        },
        error: function(error){

        }
    });
    
}

//para buscar items de productos ventas desde reultado de busqueda
function buscar_producto_resultado(id){
    var action = 'infoProductoRef2';
    var listaPrecio = $('#listaPrecio').val();
    let id_detalle = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action, id_detalle:id_detalle,listaPrecio:listaPrecio},

        success: function(response){
            if(response != 'error'){
                var info  = JSON.parse(response);
                // console.log(info);
                $('#detalle_busqueda_nombre').html("RESULTADO DE LA BUSQUEDA");
                $('#txt_cod_productoVenta').val(info.idProducto);
                $('#txt_ref_productoVenta').val(info.referencia);
                $('#txt_codigoBarra_productoVenta').val(info.codigoDeBarra);
                $('#txt_desctipcion').html(info.nombre);
                $('#txt_existencia').html(info.existencia);
                $('#txt_cant_productoVenta').val(1);
                $('#txt_precio').html(numberWithCommas(info.precioVenta));
                $('#txt_precio_total').html(numberWithCommas(info.precioVenta));
                $('#txt_porc_desc').val(0);
                $('#txt_porc_max_desc').val(info.porc_max_desc);

                //Activar cantidad
                $('#txt_cant_productoVenta').removeAttr('disabled');
                $('#txt_porc_desc').removeAttr('disabled');
                // smoothScroll('last');
                // $('#txt_cant_productoVenta').removeAttr('disabled');
                //         $('#txt_porc_desc').removeAttr('disabled');

                //mostrar boton agregar
                $('#add_product_venta').slideDown();
                function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
            }
            // else{
            //     // $('#detalle_venta').html('');
            //     // $('#detalle_totales').html('');

            // }
            // viewProcesarVenta();
        },
        error: function(error){

        }
    });
    
}

//para buscar items de productos compras desde reultado de busqueda
function buscar_producto_resultado_compra(id) {
    var action = 'infoProductoRefCom';
    let id_detalleCom = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: { action: action, id_detalleCom: id_detalleCom },

        success: function (response) {
            if (response != 'error') {
                var info = JSON.parse(response);
                // console.log(info);
                $('#detalle_busqueda_nombre').html("RESULTADO DE LA BUSQUEDA");
                $('#txt_cod_producto2').val(info.idProducto);
                $('#txt_ref_producto').val(info.referencia);
                $('#txt_codigoBarra_productoCompra').val(info.codigoDeBarra);
                $('#txt_desctipcion').html(info.nombre);
                $('#txt_existencia').html(info.existencia);
                $('#txt_cant_producto2').val(1);
                $('#txt_precio').html(numberWithCommas(info.precioCosto));
                $('#txt_precio_total').html(numberWithCommas(info.precioCosto));
                $('#txt_porc_desc_com').val(0);
                $('#txt_porc_max_desc').val(info.porc_max_desc);

                //Activar cantidad
                $('#txt_cant_producto2').removeAttr('disabled');
                $('#txt_porc_desc_com').removeAttr('disabled');

                //mostrar boton agregar
                $('#add_product_compra').slideDown();
                function numberWithCommas(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
            }
        },
        error: function (error) {
        }
    });
}
//***FIN*** - para buscar items de productos compras desde reultado de busqueda

//para buscar items de productos inventario desde resultado de busqueda
function buscar_producto_resultadoInv(id){
    var action = 'infoProductoRefInv';
    let id_detalle = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action, id_detalle:id_detalle},
        success: function(response){
            if(response != 'error'){
                var info  = JSON.parse(response);
                console.log("llegue hasta aqui");
                console.log(info);
                $('#detalle_busqueda_nombre').html("RESULTADO DE LA BUSQUEDA");
                $('#idProducto').val(info.idProducto);
                $('#referencia').val(info.referencia);
                $('#codigoDeBarra').val(info.codigoDeBarra);
                $('#producto').val(info.nombre);
                $('#cantActual').val(info.existencia);
                $('#cantInventario').val(1);
            }
        },
        error: function(error){
        }
    });
}
    // FIN

//para buscar clientes ventas desde reultado de busqueda
function buscar_cliente_resultado(id){
    var action = 'infoCliente2';
    let id_cliente = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,id_cliente:id_cliente},

        success: function(response){
            if(response != 'error'){
                var info  = JSON.parse(response);
                $('#idCliente').val(info.idCliente);
                $('#ci_cliente').val(info.ci_ruc);
                $('#nom_cliente').val(info.nombre);
                $('#dir_cliente').val(info.direccion);
                $('#cel1_cliente').val(info.celular1);
                $('#cel2_cliente').val(info.celular2);
                $('#tel1_cliente').val(info.telefono1);
                $('#tel2_cliente').val(info.telefono2);
                $('#detalle_busqueda_cliente').html("RESULTADO DE LA BUSQUEDA");
            }
        },
        error: function(error){
        }
    });
}

//para buscar proveedor - compra, desde reultado de busqueda
function buscar_proveedor_resultado(id) {
    var action = 'infoProveedor2';
    let idProveedor = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: { action: action, idProveedor: idProveedor },
        success: function (response) {
            if (response != 'error') {
                var info = JSON.parse(response);
                $('#con_proveedor').val(info.contacto);
                $('#nom_proveedor').val(info.nombre);
                $('#tel_proveedor').val(info.telefono);
                $('#dir_proveedor').val(info.direccion);
                $('#idProveedor').val(info.idProveedor);
                $('#detalle_busqueda_proveedor').html("RESULTADO DE LA BUSQUEDA");
            }
        },
        error: function (error) {
        }
    });
}
//*** FIN *** - para buscar proveedor - compra, desde reultado de busqueda

//para eliminar items de linea detalles PRESUPUESTO temp
function del_producto_detalle_presupuesto(id){
    var action = 'del_producto_detalle_presupuesto';
    var id_detalle = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action, id_detalle:id_detalle},

        success: function(response){
            if(response != 'error'){
                var info  = JSON.parse(response);
                        $('#detalle_presupuesto').html(info.detalle);
                        $('#detalle_totales_presupuesto').html(info.totales);

                        $('#txt_cod_producto').val('');
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('0');
                        $('#txt_cant_productoPresupuesto').val('0');
                        $('#txt_precio').html('0');
                        $('#txt_precio_total').html('0');

                        //bloquear cantidad
                        $('#txt_cant_productoPresupuesto').attr('disabled','disabled');

                        //ocultar el boton agregar
                        $('#add_product_presupuesto').slideUp();
            }else{
                $('#detalle_presupuesto').html('');
                $('#detalle_totales_presupuesto').html('');

            }
            viewProcesarPresupuesto();
        },
        error: function(error){

        }
    });
    
}
/*FIN ELIMINAR LINEA DE DETALLE TEMP PRESUPUESTO*/

//para eliminar items de linea detalles GASTO temp
function del_producto_detalle_gasto(id){
    var action = 'del_producto_detalle_gasto';
    var id_detalle = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action, id_detalle:id_detalle},

        success: function(response){
            if(response != 'error'){
                var info  = JSON.parse(response);
                        $('#detalle_gasto').html(info.detalle);
                        $('#detalle_totales_gasto').html(info.totales);
            }else{
                $('#detalle_gasto').html('');
                $('#detalle_totales_gasto').html('');

            }
            viewProcesarGasto();
        },
        error: function(error){

        }
    });
    
}

//*******INICIO******* para eliminar items de linea detalles COMPRA temp
function del_producto_detalleCompra(id){
    var action = 'del_producto_detalleCompra';
    var id_detalle = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action, id_detalle:id_detalle},

        success: function(response){
            if(response != 'error'){
                var info  = JSON.parse(response);
                        $('#detalle_ventaCompra').html(info.detalle);
                        $('#detalle_totalesCompra').html(info.totales);

                        $('#txt_ref_producto').val('');
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('0');
                        $('#txt_cant_producto2').val('0');
                        $('#txt_precio').html('0');
                        $('#txt_precio_total').html('0');

                        //bloquear cantidad
                        $('#txt_cant_producto2').attr('disabled','disabled');

                        //ocultar el boton agregar
                        $('#add_product_compra').slideUp();
            }else{
                $('#detalle_ventaCompra').html('');
                $('#detalle_totalesCompra').html('');
            }
            viewProcesar();
        },
        error: function(error){
        }
    });
}
//*******FIN******* para eliminar items de linea detalles COMPRA temp

//mostrar/ocultar boton procesar
function viewProcesarVenta(){
    if($('#detalle_venta tr').length > 0){
        $('#btn_facturar_venta').show();

    }else{
        $('#btn_facturar_venta').hide();
    }
}

//mostrar/ocultar boton procesar COMPRA
function viewProcesar() {
    if ($('#detalle_ventaCompra tr').length > 0) {
        $('#btn_facturar_compra').show();

    } else {
        $('#btn_facturar_compra').hide();
    }
}

//mostrar/ocultar boton procesar COMPRA
function viewProcesarPresupuesto() {
    if ($('#detalle_presupuesto tr').length > 0) {
        $('#btn_facturar_presupuesto').show();

    } else {
        $('#btn_facturar_presupuesto').hide();
    }
}

//mostrar/ocultar boton procesar GASTO
function viewProcesarGasto(){
    if($('#detalle_gasto tr').length > 0){
        $('#btn_procesar_gasto').show();
    
    }else{
        $('#btn_procesar_gasto').hide();
    }
    }



//para recargar los datos en caso de que el usuario ya cargo informaciones
function searchForDetailVenta(id){
    var action = 'searchForDetalle';
    var user = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,user:user},

        success: function(response){
            if(response != 'error'){
                var info = JSON.parse(response);
                console.log(response);
                $('#detalle_venta').html(info.detalleVenta);
                $('#detalle_totales').html(info.totalesVenta);
            }else{
                console.log('no data');
            }
            viewProcesarVenta();
        },
        error: function(error){
        }
    });
}

//para recargar los datos en caso de que el usuario ya cargo informaciones presupuesto
function searchForDetailPresupuesto(id){
    // var action = 'searchForDetalle';
    var action = 'searchForDetallePresupuesto';
    var user = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,user:user},

        success: function(response){
            if(response != 'error'){
                var info = JSON.parse(response);
                console.log(response);
                $('#detalle_presupuesto').html(info.detalleVenta);
                $('#detalle_totales_presupuesto').html(info.totalesVenta);
            }else{
                console.log('no data search detalle presupuesto');
            }
            viewProcesarPresupuesto();
        },
        error: function(error){
        }
    });
}

//para recargar los datos de GASTO en caso de que el usuario ya cargo informaciones
function searchForDetailGasto(id){
    var action = 'searchForDetalleGasto';
    var user = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,user:user},

        success: function(response){
            if(response != 'error'){
                var info = JSON.parse(response);
                //console.log(response);
                $('#detalle_gasto').html(info.detalle);
                $('#detalle_totales_gasto').html(info.totales);
            }else{
                console.log('no data');
            }
            viewProcesarGasto();
        },
        error: function(error){
        }
    });
}

//*******INICIO******* para recargar los datos en caso de que el usuario ya cargo informaciones COMPRA
function searchForDetailCompra(id){
    var action = 'searchForDetalleCompra';
    var user = id;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,user:user},

        success: function(response){
            if(response != 'error'){
                var info = JSON.parse(response);
                $('#detalle_ventaCompra').html(info.detalle);
                $('#detalle_totalesCompra').html(info.totales);
            }else{
                console.log('no data');
            }
            viewProcesar();
        },
        error: function(error){
        }
    });
}
//*******FIN******* para recargar los datos en caso de que el usuario ya cargo informaciones COMPRA

function getUrl(){
    var loc  = window.location;
    var pathName = loc.pathname.substring(0,loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0,loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}