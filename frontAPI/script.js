const form = document.querySelector('#filmesForm')
const tituloInput = document.querySelector('#tituloInput')
const diretorInput = document.querySelector('#diretorInput')
const generoInput = document.querySelector('#generoInput')
const ano_lancamentoInput = document.querySelector('#ano_lancamentoInput')
const URL = 'http://localhost:8080/filmes.php'

const tableBody = document.querySelector('#filmesTable')

function carregarFilmes() {
    fetch(URL, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
        mode: 'cors'
    })
        .then(response => response.json())
        .then(filmes => {
            tableBody.innerHTML = ''

            for (let i = 0; i < filmes.length; i++) {
                const tr = document.createElement('tr')
                const filme = filmes[i]
                tr.innerHTML = `
                    <td>${filme.id}</td>
                    <td>${filme.titulo}</td>
                    <td>${filme.diretor}</td>
                    <td>${filme.genero}</td>
                    <td>${filme.ano_lancamento}</td>
                    <td>
                        <button data-id="${filme.id}" onclick="atualizarFilme(${filme.id})">Editar</button>
                        <button onclick="excluirFilme(${filme.id})">Excluir</button>
                    </td>
                `
                tableBody.appendChild(tr)
            }

        })
}

//função para criar um filme ---------------------------------------------------------------
function adicionarFilme(e) {

    e.preventDefault()

    const titulo = tituloInput.value
    const diretor = diretorInput.value
    const genero = generoInput.value
    const ano_lancamento = ano_lancamentoInput.value

    fetch(URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `titulo=${encodeURIComponent(titulo)}&diretor=${encodeURIComponent(diretor)}&genero=${encodeURIComponent(genero)}&ano_lancamento=${encodeURIComponent(ano_lancamento)}`
    })
        .then(response => {
            if (response.ok) {
                carregarFilmes()
                tituloInput.value = ''
                diretorInput.value = ''
                generoInput.value = ''
                ano_lancamentoInput.value = ''
            } else {
                console.error('Erro ao add o Filme')
                alert('Erro ao add o Filme')
            }
        })
}

//função para atualizar um filme -----------------------------------------------------------------
function atualizarFilme(id) {
    const novoTitulo = prompt("Digite o novo titulo")
    const novoDiretor = prompt("Digite o novo diretor")
    const novoGenero = prompt("Digite o novo genero")
    const novoAno = prompt("Digite o novo ano")

    if (novoTitulo && novoDiretor && novoGenero && novoAno) {
        fetch(`${URL}?id=${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: `titulo=${encodeURIComponent(novoTitulo)}&diretor=${encodeURIComponent(novoDiretor)}&genero=${encodeURIComponent(novoGenero)}&ano_lancamento=${encodeURIComponent(novoAno)}`
        })
        .then(response =>{
            if(response.ok){
                carregarFilmes()
            } else{
                console.error()
            }
        })

    }
}

carregarFilmes()