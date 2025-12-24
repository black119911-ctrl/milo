document.addEventListener('DOMContentLoaded', function() {
    const loadButton = document.querySelector('#loadmore-serts');
    let currentOffset = 6;

    loadButton.addEventListener('click', async function(event) {
        event.preventDefault();
        await loadMoreCertificates(currentOffset);
    });

    async function loadMoreCertificates(offset) {
        const response = await fetch(`/api/sertificates/load?offset=${offset}`);
        const result = await response.json();

        if(result.success) {
            result.data.forEach((item) => {
                // const div = document.createElement('div');
                // div.classList.add('certificate');
                
                let sertLink = `
                <a href="https://milovan4ik.ru/assets/img/serts/${item.image}" class="sertificates__block">
                    <img src="https://milovan4ik.ru/assets/img/serts/${item.thumb}" alt="">
                </a>`
                document.querySelector('#sertificates').innerHTML += sertLink
            });

            currentOffset += 3; // увеличиваем смещение на три элемента
            
        } else {
            loadButton.style.display = 'none'; // скроем кнопку, если больше сертификатов нет
        }
    }

});