<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title>Provedores</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link href="dashboard.css" rel="stylesheet">      
        
        <style>
            .axis path,
            .axis line {
                fill: none;
                stroke: #000;
                shape-rendering: crispEdges;
            }

            .x.axis path {
                display: none;
            }

            .line {
                fill: none;
                stroke: steelblue;
                stroke-width: 1.5px;
            }            
        </style>
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">GUES - Provedores</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Settings</a></li>
                        <li><a href="#">Profile</a></li>
                        <li><a href="#">Help</a></li>
                    </ul>
                    <form class="navbar-form navbar-right">
                        <input type="text" class="form-control" placeholder="Search..." id="dolar">
                    </form>
                </div>
            </div>
        </nav>
        
        <div class="container-fluid">            
            <div class="row">
                <!--<div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                        <li class="active"><a href="#">Por Fecha<span class="sr-only">(current)</span></a></li>
                        <li><a href="#">Por Provedor</a></li>
                        <li><a href="#">Por Producto</a></li>
                        <li><a href="#">Por Precio</a></li>
                    </ul>                    
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">-->
                <div class="col-xs-12 main">
                    <br>
                    <div class="row">
                        <div class="col-xs-6">
                            <a type="button" class="btn btn-default btn-block" href="clientes-precios.php">Clientes</a>
                        </div>
                        <div class="col-xs-6">                    
                            <a type="button" class="btn btn-default btn-block active" href="provedores-precios.php">Provedores</a>
                        </div>
                    </div>
                    <br>                    
                    <h1 class="page-header">Productos</h1>
                    <div class="row" id="product-div">                        
                    </div>
                    <h2 class="sub-header">Tabla de Precios</h2>                
                    <div class="table-responsive">
                        <table class="table table-striped item-table add">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <td class="producto"><input type="text" class="form-control"/></td>
                                    <td class="provedor"><input type="text" class="form-control"/></td>
                                    <td class="precio"><input type="number" class="form-control"/></td>
                                    <td class="moneda">
                                        <select class="currency-select form-control"></select>
                                    </td>
                                    <td class="unidad">
                                        <select class="unit-select form-control"></select>
                                    </td>
                                    <td class="fecha"><input type="date" class="form-control"/></td>
                                </tr>                        
                            </tbody>                            
                        </table>
                        <input type="button" id="add-button" class="btn btn-default btn-block" value="Agregar y Guardar"/>
                        <table class="table table-striped item-table display">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
                        <input type="button" id="save-button" class="btn btn-default btn-block" value="Guardar"/>
                    </div>
                    <h2 class="sub-header">Gráfica</h2>
                    <div class="row">
                        <div class="col-xs-12 graph"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Libraries and Scripts
        ================================================== -->
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <!-- d3 -->        
        <script src="//d3js.org/d3.v3.min.js"></script>
        <!-- custom script -->
        <script>
            //INITIALIZE VARIABLES
            dataset = [];
            currSort = sortNumber;
            currField = "Fecha";
            
            //NO CACHE AJAX
            $.ajaxSetup({ cache: false });
            
            //DOLAR
            var dolarBanxico = <?php 
                    echo file_get_contents('http://www.banxico.org.mx/pubDGOBC_Sisfix-war/Sisfix_JSON');
                ?>;
            var dolar = dolarBanxico.tcfix;
            $("#dolar").val("1 USD = " + dolar + " MXN").attr("val", dolar);
            
            //FECHAS
            var format = d3.time.format("%Y-%m-%d");

            //UNIDADES
            var unidades = ['litro', 'kilo'];
            
            //MONEDAS
            var monedas = ['MXN', 'USD'];
            
            //COLUMNAS
            var columnas = ['Producto', 'Provedor', 'Precio', 'Moneda', 'Unidad', 'Fecha'];
            
            //BUILD DEFAULT TABLE
            LoadData(currSort, currField);
            
            
            //GENERAL FUNCTIONS
            ///////////////////
            //GET UNIQUES
            function GetUniques(data, field) {
                var flags = [], output = [], l = data.length, i;
                for( i=0; i<l; i++) {
                    if( flags[data[i][field]]) continue;
                    flags[data[i][field]] = true;
                    output.push(data[i][field]);
                }
                return output;
            }
            
            //SORT STRINGS
            function sortString( key ) {
                return function(a, b) {
                    return a[key].toUpperCase().localeCompare(b[key].toUpperCase());
                }
            }
            
            //SORT NUMBERS
            function sortNumber( key ) {
                return function(a, b) {
                    return b[key] - a[key];
                }
            }
            
            //DELETE
            //SIDE BAR SORT
            /* $(".sidebar .nav-sidebar li").on("click", function() {
                $(".sidebar .nav-sidebar li").removeClass("active");
                $(this).addClass("active");
                
                key = $(this).children("a").text().split(" ")[1];                
                
                try {
                    var data = dataset.sort(sortString(key));    
                } catch (err) {
                    var data = dataset.sort(sortNumber(key));    
                }                
                BuildTable(data);
            }); */
            
            //CONTROLS
            /////////////////
            //ADD UNIT SELECT
            function AddUnitSelect() {
                var s = $(".add .unidad select").addClass("unit-select");
                $.each(unidades, function(i, unidad) {
                                    $('<option />', {value: unidad, text: unidad}).appendTo(s);
                });
                s.val(unidades[0]);
            }
            
            //ADD CURRENCY SELECT
            function AddCurrencySelect() {
                var s = $(".add .moneda select").addClass("currency-select");
                $.each(monedas, function(i, moneda) {
                                    $('<option />', {value: moneda, text: moneda}).appendTo(s);
                });
                s.val(monedas[0]);
            }            
            
            //SET CURRENT DATE
            function SetCurrentDate() {
                var date = new Date();                
                $(".add .fecha input").val(format(date));
            }
            
            //VALIDATE FIELDS
            function ValidateFields() {
                var producto = $('.add .producto input').val();
                var provedor = $('.add .provedor input').val();
                var precio = $('.add .precio input').val();
                var unidad = $('.add .unidad select').val();
                var fecha = $('.add .fecha input').val();
                
                var allFieldsCheck = producto && provedor && precio && unidad && fecha;
                if (!allFieldsCheck) {
                    alert('Llena todos los campos y vuelve a intentar.');
                    return false;
                }
                
                var precioCheck = /-?(\d+|\d+\.\d+|\.\d+)([eE][-+]?\d+)?/.test(precio);
                if (!precioCheck) {
                    alert('El precio debe estar compuesto de números.')
                    return false;
                }
            
                return true;
            }
            
            //PRODUCT BUTTONS
            function AddProductButtons(data) {
                var productDiv = $("#product-div").html("");
                var uniqueProducts = GetUniques(data, "Producto").sort();
                
                $.each(uniqueProducts, function(i, product) { 
                    var $div = $('<div class="col-xs-3">');
                    var $button = $('<input type="button" class="btn btn-primary btn-block">').val(product);
                    $($div).append($button);
                    $(productDiv).append($div);
                });
                
                $('#product-div div input').on('click', function() {
                    $('#product-div div input').removeClass("active")
                    $(this).addClass("active");
                    var producto = $(this).val();                    
                    //TABLE
                    FilterTable("producto", producto);
                    //GRAPHIC
                    var data = dataset.filter(function(obj) { return obj.Producto == producto });
                    GenerateGraphic(data);
                });
            }
            
            //ADD BUTTON
            $("#add-button").on("click", function () { 
              if(ValidateFields()) {
                  SaveData(true);
              }
            });
            
            
            //SAVE BUTTON
            $("#save-button").on("click", function () { 
                  SaveData(false);
            });
            
            //DATA
            ///////////
            //LOAD DATA
            function LoadData(sortFunction, field) {
                $.getJSON( "provedores-data.json", function( data, error ) {
                    $.each(data, function(i, d) {
                        data[i]["Fecha"] = format.parse(data[i]["Fecha"]);
                    });
                    dataset = data;
                    
                    dataset = dataset.sort(sortFunction(field));
                    BuildTable(dataset);
                    AddUnitSelect();
                    AddCurrencySelect();
                    SetCurrentDate();
                    AddProductButtons(dataset);
                });
            }
            
            //CREATE JSON
            function CreateJSON(update) {                
                var json = [];
                
                //DELETE
                //Save Keys
                var keys = [];
                $.each($(".item-table.display thead tr td"), function(i, item) {
                    keys.push($(item).text());
                });
                
                //Save Rows
                $.each($(".item-table" + update + " tbody tr"), function(i, item) {
                    var row = {};
                    $.each($(item).find("td input, td select"), function(j, val) {
                      row[columnas[j]] = $(val).val();
                    });
                    json.push(row);
                });
                
                return json;
            }
            
            //SAVE DATA
            function SaveData(isUpdate) {                
                if (isUpdate) {
                    var selector = "";
                } else {
                    var selector = ".display";
                }
                
                var data = CreateJSON(selector);
                
                //SAVE FILE IN SERVER
                $.ajax({
                    type : "POST",
                    url : "provedores-ajax.php",
                    data : {
                        json : JSON.stringify(data)
                    }}).done(function() {
                        LoadData(currSort, currField);
                        if (isUpdate) {
                            $(".item-table.add tbody input, .item-table.add tbody select").val("");
                        }
                        alert("La base de datos se ha actualizado correctamente.");
                    });                
            }
            
            
            //TABLE
            /////////////
            //BUILD TABLE
            function BuildTable(data) {
                //HEAD
                $(".item-table thead").html("");
                var $head = $("<tr>");
                $.each(columnas, function(key, columna) {
                   var $tr = $head.append( $('<td>').text(columna) );
                });
                $head.appendTo(".item-table thead");

                //BODY                                 
                $(".item-table.display tbody").html("");
                $.each(data, function(i, item) {        
                    var $tr = $('<tr>').append(
                        $('<td>').addClass("producto").append($('<input type="text" class="form-control">').val(item.Producto)),
                        $('<td>').addClass("provedor").append($('<input type="text" class="form-control">').val(item.Provedor)),
                        $('<td>').addClass("precio").append($('<input type="text" class="form-control">').val(item.Precio).attr("val", item.Precio)),
                        $('<td>').addClass("moneda").append(
                            function() { var s = $('<select class="currency-select form-control">');
                                $.each(monedas, function(i, moneda) {
                                    $('<option />', {value: moneda, text: moneda}).appendTo(s);
                                });
                                s.val(item.Moneda);
                                return s;
                            }),
                        $('<td>').addClass("unidad").append(
                            function() { var s = $('<select class="unit-select form-control">');
                                $.each(unidades, function(i, unidad) {
                                    $('<option />', {value: unidad, text: unidad}).appendTo(s);
                                });
                                s.val(item.Unidad);
                                return s;
                            }),
                        $('<td>').addClass("fecha").append($('<input type="date" class="form-control">').val(format(item.Fecha)))
                    );                    
                    $tr.appendTo(".item-table.display tbody")
                });                                
            }
            
            //FILTER TABLE
            function FilterTable(key, value) {
                var rows = $(".item-table.display tbody tr").css("display", "table-row");
                $.each(rows, function(i, row){
                    var input = $(row).find("." + key + " input")[0];
                    var currValue = $(input).val();
                    if (currValue != value) { $(row).css("display", "none"); }
                });
            }
            
            //GRAPHIC
            /////////////////
            //GET DATA SERIES
            function GetDataSeries(data) {
                var provedores = GetUniques(data, "Provedor").sort();
                var fechas = GetUniques(data, "Fecha").map(function(fecha){ return format(fecha); });
                var dataSeries = []
                $.each(fechas, function(i, fecha) {
                    var obj = {};
                    obj.Fecha = fecha;
                    $.each(provedores, function(i, provedor) {
                       obj[provedor] = NaN;
                    });
                    dataSeries.push(obj);
                });
                $.each(data, function(i, d) {
                    var obj = dataSeries.filter(function( obj ) {
                        return obj.Fecha == format(d.Fecha);
                    })[0];
                    obj[d.Provedor] = d.Precio;
                });
                
                return dataSeries;
            }
            
            //GENERATE GRAPHIC
            function GenerateGraphic(data) {
                var data = GetDataSeries(data);
                
                var margin = {top: 20, right: 20, bottom: 50, left: 50},
                    width = $("div .graph").width() - margin.left - margin.right,
                    height = ($("div .graph").width()/2) - margin.bottom;

                var x = d3.time.scale()
                    .range([0, width]);

                var y = d3.scale.linear()
                    .range([height, 0]);

                var color = d3.scale.category10();
                
                var xAxis = d3.svg.axis()
                    .scale(x)
                    .orient("bottom")
                    .tickFormat(d3.time.format("%d/%m/%y"));

                var yAxis = d3.svg.axis()
                    .scale(y)
                    .orient("left");

                var line = d3.svg.line()
                    .defined(function(d) { return !isNaN(d.Precio); })
                    //.defined(function(d) { return d; })
                    .x(function(d) { return x(d.Fecha); })
                    .y(function(d) { return y(d.Precio); });

                var svg = d3.select("div .graph").html("").append("svg")
                    .attr("width", width + margin.left + margin.right)
                    .attr("height", height + margin.top + margin.bottom)
                    .append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
                
                color.domain(d3.keys(data[0]).filter(function(key) { return key !== "Fecha"; }));
                
                var provedores = color.domain().map(function(name) {
                    return {
                        Provedor: name,
                        Valores: data.map(function(d) {
                            return {Fecha: format.parse(d.Fecha), Precio: +d[name]};
                        })
                    };
                });
                
                x.domain([
                    d3.min(provedores, function(c) { return d3.min(c.Valores, function(v) { return v.Fecha; }); }),
                    d3.max(provedores, function(c) { return d3.max(c.Valores, function(v) { return v.Fecha; }); })
                ]);
                y.domain([
                    0,
                    d3.max(provedores, function(c) { return d3.max(c.Valores, function(v) { return v.Precio; }); })
                ]);            
                
                svg.append("g")
                    .attr("class", "x axis")
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis)
                    .selectAll("text")
                    .attr("y", 0)
                    .attr("x", 9)
                    .attr("dy", ".35em")
                    .style("font-size","10px")
                    .attr("transform", "rotate(90)")
                    .style("text-anchor", "start");                    

                svg.append("g")
                    .attr("class", "y axis")
                    .call(yAxis)
                    .append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 6)
                    .attr("dy", ".71em")
                    .style("text-anchor", "end")
                    .text("Precio ($)");
                
                var provedorLinea = svg.selectAll(".provedorLineGroup")
                    .data(provedores)
                    .enter().append("g")
                    .attr("class", ".provedorLineGroup");
                
                provedorLinea.append("path")
                    .attr("class", "line")
                    .attr("d", function(d) { return line(d.Valores); })
                    .style("stroke", function(d) { return color(d.Provedor); });                
                
                //PUNTOS
                provedoresDot = [];
                $.each(provedores, function(i, provedor) {
                    $.each(provedor.Valores, function(j, valores) {
                       if (!isNaN(valores.Precio)) {
                           provedoresObj = {};
                           provedoresObj.Provedor = provedor.Provedor;
                           provedoresObj.Fecha = valores.Fecha;
                           provedoresObj.Precio = valores.Precio;
                           provedoresDot.push(provedoresObj);
                       }
                    });
                });

                var provedorPunto = svg.selectAll(".provedorDotGroup")
                    .data(provedoresDot)
                    .enter().append("g")
                    .attr("class", ".provedorDotGroup");
                
                provedorPunto.append("circle")
                    .attr("class", "dot")
                    .attr("cx", function(d) { return x(d.Fecha); })
                    .attr("cy", function(d) { return y(d.Precio); })
                    .style("fill", function(d) { return color(d.Provedor); })
                    .attr("r", 3.5); 
                
                //LEGEND
                var legendRectSize = 18;
                var legendSpacing = 4;
                
                var legend = svg.selectAll('.legend')
                    .data(color.domain())
                    .enter()
                    .append('g')
                    .attr('class', 'legend')
                    .attr('transform', function(d, i) {
                        var h = legendRectSize + legendSpacing;
                        var offset =  h * color.domain().length / 2;
                        var horz = width - (2 * legendRectSize + margin.right);
                        var vert = height - (i * h + margin.bottom);
                        return 'translate(' + horz + ',' + vert + ')';
                    });
                
                legend.append('rect')
                    .attr('width', legendRectSize)
                    .attr('height', legendRectSize)
                    .style('fill', color)
                    .style('stroke', color);
                
                legend.append('text')
                    .attr('x', legendRectSize + legendSpacing)
                    .attr('y', legendRectSize - legendSpacing)
                    .text(function(d) { return d; });                
            }
            
            //TO DO: WORK ON GRAPH RESPONSIVENESS
            function resize() {
                /* Find the new window dimensions */
                var margin = {top: 20, right: 20, bottom: 50, left: 50},
                    width = $("div .graph").width() - margin.left - margin.right,
                    height = ($("div .graph").width()/2) - margin.bottom;

                /* Update the range of the scale with new width/height */
                var x = d3.time.scale()
                    .range([0, width]);
                var y = d3.scale.linear()
                    .range([height, 0]);

                /* Update the axis with the new scale */
                var xAxis = d3.svg.axis()
                    .scale(x)
                    .orient("bottom")
                    .tickFormat(d3.time.format("%d/%m/%y"));

                var yAxis = d3.svg.axis()
                    .scale(y)
                    .orient("left");
                
                
                svg.select(".x .axis")
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis)

                svg.select(".y .axis")
                    .call(yAxis)
                svg
                    .attr("width", width + margin.left + margin.right)
                    .attr("height", height + margin.top + margin.bottom)
                
                /* Force D3 to recalculate and update the line */                                
                svg.selectAll(".line")
                    .attr("d", function(d) { return line(d.Valores); })

                svg.selectAll(".plot")
                    .attr("cx", function(d) { return x(d.Fecha); })
                    .attr("cy", function(d) { return y(d.Precio); })
            }
 
            //d3.select(window).on('resize', resize); 
        </script>
    </body>
</html>