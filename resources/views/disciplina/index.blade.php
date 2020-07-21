@extends('templates.main', ['titulo' => "Disciplina"])

@section('conteudo')
 
     <div class='row'>
         <div class='col-sm-12'>
            <button class="btn btn-primary btn-block" onclick="criar()">
                <b>Cadastrar Nova Disciplina</b>
            </button>
         </div>
     </div>
     <br>
 
     @component(
         'components.tablelistDisciplina', [
             "header" => ['Nome', 'Eventos'],
             "data" => $disciplinas
         ]
     )
     @endcomponent

     <div class="modal" tabindex="-1" role="dialog" id="modalDisciplina">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formDisciplinas">
                    <div class="modal-header">
                        <h5 class="modal-title">Nova Disciplina</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" class="form-control">
                        <div class='col-sm-12'>
                            <label><b>Nome</b></label>
                            <input type="text" class="form-control" name="nome" id="nome" required>
                        </div>
                        <div class='col-sm-12' style="margin-top: 10px">
                            <label>Número de bimestres</label>
                            <input type="number" class="form-control" name="num_de_bimestres" id="num_de_bimestres" required>
                        </div>
                        <div class='col-sm-12' style="margin-top: 10px">
                            <label>Componente Curricular</label>
                            <select name="componente_id" id="componente_id" class="form-control" required>
                                
                            </select>
                        </div>
                        <div class='col-sm-12' style="margin-top: 10px">
                            <label>Curso</label>
                            <select name="curso" id="curso" class="form-control" required>
                                
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modalPeso">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formPeso">
                    <div class="modal-header">
                        <h5 class="modal-title">Configuração de pesos</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="idPeso" class="form-control">
                        <input type="hidden" id="idDisc" class="form-control">
                        <div class='col-sm-12'>
                            <label><b>Trabalho</b></label>
                            <input type="text" class="form-control" name="trabalho" id="trabalho" required>
                        </div>
                        <div class='col-sm-12' style="margin-top: 10px">
                            <label>Avaliação</label>
                            <input type="text" class="form-control" name="avaliacao" id="avaliacao" required>
                        </div>
                        <div class='col-sm-12' style="margin-top: 10px">
                            <label>1º Bimestre</label>
                            <input type="text" class="form-control" name="pri_bim" id="pri_bim" required>
                        </div>
                        <div class='col-sm-12' style="margin-top: 10px">
                            <label>2º Bimestre</label>
                            <input type="text" class="form-control" name="seg_bim" id="seg_bim" required>
                        </div>
                        <div class='col-sm-12' style="margin-top: 10px">
                            <label>3° Bimestre</label>
                            <input type="text" class="form-control" name="ter_bim" id="ter_bim" required>
                        </div>
                        <div class='col-sm-12' style="margin-top: 10px">
                            <label>4º Bimestre</label>
                            <input type="text" class="form-control" name="qua_bim" id="qua_bim" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalInfo">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informações da turma</h5>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="cancel" class="btn btn-success" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
     </div>
@endsection

@section('script')


    <script type="text/javascript">
        function loadSelects() {
            $.getJSON('/api/cursos/load', function (data) {
                for(i = 0; i < data.length; i++) {
                    item = '<option value="'+data[i].id+'">'+data[i].nome+'</option>';
                    $('#curso').append(item);
                }
            });

            $.getJSON('/api/componentes/load', function (data) {
                for(i = 0; i < data.length; i++) {
                    item = '<option value="'+data[i].id+'">'+data[i].nome+'</option>';
                    $('#componente_id').append(item);
                }
            });
        }

        $(function() {
            loadSelects();
        })

        
        function criar() {
            $('#modalDisciplina').modal().find('.modal-title').text("Nova Disciplina");
            $('#nome').val('');
            $('#num_de_bimestres').val('');
            $('#component_id').val('');
            $('#curso').val('');
            $('#modalDisciplina').modal('show');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        })

        $("#formDisciplinas").submit( function(event) {
            event.preventDefault();
            if($("#id").val() != '') {
                update( $("#id").val() );
            }
            else {
                insert();
            }
            $('#modalDisciplina').modal('hide')
        })

        $("#formPeso").submit( function(event) {
            event.preventDefault();
            if($("#idPeso").val() != '') {
                updatePeso( $("#idDisc").val() );
            }
            else {
                insertPeso();
            }
            $('#modalPeso').modal('hide')
        })

        function insertPeso() {
            pesos = {
                trabalho: $('#trabalho').val(),
                avaliacao: $('#avaliacao').val(),
                pri_bim: $('#pri_bim').val(),
                seg_bim: $('#seg_bim').val(),
                ter_bim: $('#ter_bim').val(),
                qua_bim: $('#qua_bim').val(),
                disciplina_id: $('#idDisc').val(),
            };
            $.post("/api/pesos", pesos, function(data) {
                console.log('200 OK PESO');
            });
        }

        function updatePeso(id) {
            pesos = {
                trabalho: $('#trabalho').val(),
                avaliacao: $('#avaliacao').val(),
                pri_bim: $('#pri_bim').val(),
                seg_bim: $('#seg_bim').val(),
                ter_bim: $('#ter_bim').val(),
                qua_bim: $('#qua_bim').val(),
                disciplina_id: $('#idDisc').val(),
            };

            $.ajax({
                type: "PUT",
                url: "/api/pesos/"+id,
                context: this,
                data: pesos,
                success: function (data) {
                    console.log(data);
                    console.log('edicao peso ok');
                },
                error: function(error) {
                    console.log(error);
                }
            })
        }

        function insert() {
            disciplinas = {
                nome: $("#nome").val(),
                num_de_bimestres: $("#num_de_bimestres").val(),
                componente_id: $("#componente_id").val(),
                curso: $("#curso").val(),
            };
            $.post("/api/disciplinas", disciplinas, function(data) {
                novaDisciplina = JSON.parse(data);
                linha = getLin(novaDisciplina);
                $('#tabela>tbody').append(linha);
            });
        }

        function update(id) {
            disciplinas = {
                nome: $("#nome").val(),
                num_de_bimestres: $("#num_de_bimestres").val(),
                componente_id: $("#componente_id").val(),
                curso: $("#curso").val(),
            };

            $.ajax({
                type: "PUT",
                url: "/api/disciplinas/"+id,
                context: this,
                data: disciplinas,
                success: function (data) {
                    linhas = $("#tabela>tbody>tr");
                    e = linhas.filter( function(i, e) {
                        const dataParse = (JSON.parse(data));
                        return e.cells[0].textContent == dataParse.id;
                    } );
                    console.log(e);

                    if(e) {
                        e[0].cells[1].textContent = disciplinas.nome;
                    }
                },
                error: function(error) {
                    alert('ERRO - UPDATE');
                    console.log(error);
                }
            })
        }

        function getLin(disciplina) {
            var linha = 
            "<tr style='text-align: center'>"+
                "<td style='display: none'>"+ disciplina.id +"</td>"+
                "<td>"+ disciplina.nome +"</td>"+
                "<td>"+
                    "<a nohref style='cursor: pointer' onclick='visualizar("+disciplina.id+")'><img src='{{ asset('img/icons/info.svg') }}'></a>"+
                    "<a nohref style='cursor: pointer' onclick='editar("+disciplina.id+")'><img src='{{ asset('img/icons/edit.svg') }}'></a>"+
                    "<a nohref style='cursor: pointer' onclick='peso(\""+disciplina.id+"\",\""+disciplina.nome+"\")'><img src='{{ asset('img/icons/peso.svg') }}'></a>"+
                    "<a nohref style='cursor: pointer' onclick='editar("+disciplina.id+")'><img src='{{ asset('img/icons/conceito.svg') }}'></a>"+
                "</td>"+
            "</tr>";

            return linha;
        }

        function visualizar(id) { 
            $('#modalInfo').modal().find('.modal-body').html("");

            $.getJSON('/api/disciplinas/'+id, function(data) {
                let nome_curso = '';
                $.getJSON('/api/cursos/'+data.curso_id, function(dataCurso) {
                    $.getJSON('/api/componentes/'+data.curso_id, function(dataComponente) {

                        $('#modalInfo').modal().find('.modal-body').append("<p>ID: <b>"+ data.id +"</b></p>");
                        $('#modalInfo').modal().find('.modal-body').append("<p>Nome: <b>"+ data.nome +"</b></p>");
                        $('#modalInfo').modal().find('.modal-body').append("<p>Bimestres: <b>"+ data.num_de_bimestres +"</b></p>");
                        $('#modalInfo').modal().find('.modal-body').append("<p>Componente curricular: <b>"+ dataComponente.nome +"</b></p>");
                        $('#modalInfo').modal().find('.modal-body').append("<p>Curso: <b>"+ dataCurso.nome +"</b></p>");

                        $('#modalInfo').modal('show');
                    });
                });
            });
        }

        function editar(id) { 
            $('#modalDisciplina').modal().find('.modal-title').text("Alterar Turma");

            $.getJSON('/api/disciplinas/'+id, function(data) {
                $('#id').val(data.id);
                $('#nome').val(data.nome);
                $('#num_de_bimestres').val(data.num_de_bimestres);
                $('#componente_id').val(data.componente_id);
                $('#curso').val(data.curso);
                $('#modalDisciplina').modal('show');
            });
        }

        function peso(id, nomeDisciplina) { 
            $('#modalPeso').modal().find('.modal-title').text("Configurar peso: "+nomeDisciplina);
            $('#idDisc').val(id)

            $.ajax({
                type: "GET",
                url: "/api/pesos/"+id,
                context: this,
                success: function (data) {
                    const json = JSON.parse(data);
                    $('#idPeso').val(json.id);
                    $('#trabalho').val(json.trabalho);
                    $('#avaliacao').val(json.avaliacao);
                    $('#pri_bim').val(json.pri_bim);
                    $('#seg_bim').val(json.seg_bim);
                    $('#ter_bim').val(json.ter_bim);
                    $('#qua_bim').val(json.qua_bim);
                },
                error: function(error) {
                    console.log(error);
                }
            })

            $('#modalPeso').modal('show');
        }

    </script>

@endsection