$(document).ready(() => {
    /*
        1ª Tarefa (Incluir conteúdos na página principal através do Ajax)
            - Há três maneiras, através do load(), do get() e do post().
            - load tem uma sintaxe mais curta e internamente é feita uma requisição get()
            - get tem uma sintaxe mais longa e diferente do load
            - load tem uma sintaxe igual ao do get
    */
	$('#documentacao').on('click', () => {
        // load $('#pagina').load('documentacao.html')
        // get $.get('documentacao.html', data => {$('#pagina').html(data)})
        $.post('documentacao.html', data => {
            $('#pagina').html(data)
        })
    })

    $('#suporte').on('click', () => {
      // load  $('#pagina').load('suporte.html')
      // get $.get('suporte.html', data => {$('#pagina').html(data)})
        $.post('suporte.html', data => {
            $('#pagina').html(data)
        })

    })

    /*
        3ª Tarefa (Incluir dados do banco ao front-end através do Ajax com jQuery)
            - a) Identificar quem será o responsável por disparar a lógica da inclusão dos dados (select)
            - b) Criar a função ajax passando os dados corretamente
            - c) Separar mês e ano do valor do select, através da função explode do PHP ( c) está no app.php ) 
            - d) Trocar o tipo de resposta para tipo Json e encaminhar pelo PHP em um formato json também
            - e) Em caso de sucesso pegar o item json e apresentar no HTML
    */
   $('#competencia').on('change', e => {
       let competencia = $(e.target).val()
       /*
        type = método
        url = caminho/script php
        data = responsável por disparar a lógica
        success = instrução no caso de sucesso
        error = instrução no caso de apresentação de algum erro
       */
       $.ajax({
            type: 'GET',
            url: 'app.php',
            data: `competencia=${competencia}`,
            // d)
            dataType: 'json',
            success: dados => { 
                $('#numeroVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)
                $('#clientesAtivos').html(dados.clientesAtivos)
                $('#clientesInativos').html(dados.clientesInativos)
                $('#criticas').html(dados.criticas)
                $('#elogios').html(dados.elogios)
                $('#sugestoes').html(dados.sugestoes)
                $('#totalDespesas').html(dados.totalDespesas)
            },
            error: erro => { console.log(erro)}
       })
   })

})