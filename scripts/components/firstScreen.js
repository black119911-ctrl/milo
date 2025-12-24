// // Функция для вывода текста с эффектом рукописного набора
// function writeText(elementId, textToWrite) {
//     const element = document.getElementById(elementId);
//     let index = 0;

//     // Функциональность: добавляем символы по одному с интервалом
//     function type() {
//         if (index < textToWrite.length) {
//             element.innerHTML += textToWrite.charAt(index++);

//             // Случайная пауза между символами для реализма
//             setTimeout(type, Math.random() * 100 + 50); // Интервал 50-150 мс
//         }
//     }

//     type(); // Запускаем процесс
// }

// setTimeout(() => {
//     writeText('typing-text', 'Косметика, вдохновлённая природой и тобой');
// }, 2200);
