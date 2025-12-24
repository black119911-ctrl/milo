<section class="section">
    <div class="container">
        <div class="section__title">
            <h2>Сертификаты и дипломы</h2>
        </div>
        <div class="sertificates" id="sertificates">
        <?php foreach ($sertificates as $sert) : ?>
            <a href="https://milovan4ik.ru/assets/img/serts/<?= $sert['image'] ?>" target="_blank" class="sertificates__block">
                <img src="https://milovan4ik.ru/assets/img/serts/<?= $sert['thumb'] ?>" alt="">
            </a>
        <?php endforeach ?>
        </div>
        <div class="sertificates__loadmore-wrapper">
            <button class="btn__white sertificates__loadmore" id="loadmore-serts">Загрузить ещё</button>
        </div>
    </div>
</section>