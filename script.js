$(document).ready(() => {
    /*
        1º Tarefa (Incluir conteúdos na página principal através do Ajax)
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
})