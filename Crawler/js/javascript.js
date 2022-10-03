function Mudarestado(el) {
    var display = document.getElementById(el).style.display;
    var botao = document.getElementById("meuBotao");

    if(display == "none") {
        document.getElementById(el).style.display = 'block';
        botao.innerHTML = "Esconder";
    }
    else {
        document.getElementById(el).style.display = 'none';
        botao.innerHTML = "Mostrar";
    }
}