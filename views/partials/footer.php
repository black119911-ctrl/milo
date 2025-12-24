<footer>
    <div class="container">
        <div class="footer">
            <div class="footer__block footer__logo">
                <div class="footer__logo-img">
                    <img src="/images/svg/logo-white.svg" alt="Милованчик лого">
                </div>
                <div class="footer__logo-copyright">© <?= date('Y') ?> | Все права защищены</div>
            </div>
            <div class="footer__block">
                <a href="/privacy-policy">Согласие на обработку персональных данных</a>
                <a href="#">Согласие на обработку данных, разрешенных субъектом
                    персональных данных для распространения</a>
            </div>
            <div class="footer__block">
                <a href="#">Политика конфиденциальности</a>
                <a href="#">Договор оферты</a>
                <a href="#">Юридическая информация</a>
            </div>
            <div class="footer__block footer__block--social">
                <a href="#" class="footer__block--social-link">
                    <img src="../../images/svg/whatsapp-white.svg" alt="WhatsApp иконка">
                    <span>WhatsApp</span>
                </a>
                <a href="#" class="footer__block--social-link">
                    <img src="../../images/svg/telegram-white.svg" alt="Telegram иконка">
                    <span>Telegram</span>
                </a>
                <a href="#" class="footer__block--social-link">
                    <img src="../../images/svg/bot-white.svg" alt="Телеграм бот иконка">
                    <span>Служба поддержки</span>
                </a>
            </div>
        </div>
    </div>
</footer>

<?php if (isset($resources['scripts']) && is_array($resources['scripts'])) : ?>
<?php foreach ($resources['scripts'] as $script) : ?>
<script src="<?= $script ?>"></script>
<?php endforeach ?>
<?php endif ?>

</body>

</html>