document.addEventListener('DOMContentLoaded', () => {

    // impedir valores inválidos nos inputs quantidade
document.querySelectorAll(".js_qty").forEach(input => {

    input.addEventListener("input", () => {

        // se ficar vazio, volta a 1
        if (input.value === "") {
            input.value = 1;
            return;
        }

        let val = parseInt(input.value);

        // Se invalido ou negativo volta a 1
        if (isNaN(val) || val < 1) {
            input.value = 1;
        }

        // garantir que não ultrapassa o stock máximo disponível
        let max = parseInt(input.max);
        if (!isNaN(max) && val > max) {
            input.value = max;
        }
    });

    input.addEventListener("blur", () => {
        // se o utilizador apagar e sair do campo
        if (input.value === "" || parseInt(input.value) < 1) {
            input.value = 1;
        }
    });

});
    const buttons = document.querySelectorAll('.btn_add_cart');

    buttons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();

            const produtoId = button.dataset.id;

            // procurar o input quantidade dentro do mesmo card
            const card = button.closest('.card');
            const qtyInput = card.querySelector('.js_qty');
            let quantidade = parseInt(qtyInput.value);
            if (isNaN(quantidade) || quantidade < 1) {
                quantidade = 1;
                qtyInput.value = 1;
            }

            fetch('actions/add_to_cart.php?id_produto=' + produtoId + '&quantidade=' + quantidade)
                .then(response => response.json())
                .then(data => {

                    if (data.status === 'ok') {

                        document.querySelector('.js_total').textContent =
                            '€' + parseFloat(data.total).toFixed(2);

                        document.querySelector('.js_cart_count').textContent = data.total_itens;

                        // Feedback visual no botão
                        const originalText = button.textContent;
                        button.textContent = 'Adicionado!';
                        
                        // Reset de texto
                        setTimeout(() => {
                            button.textContent = originalText;
                        }, 1000);

                    } else if (data.status === 'stock_insuficiente') {
                        alert(data.msg);
                    } else {
                        console.error("Erro:", data.msg);
                    }

                })
            .catch(error => console.error('Erro na requisição:', error));
        });
    });
});