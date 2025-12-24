// scripts/libs/alfa-proxy-interceptor.js
(function() {
    'use strict';

    console.log('🔄 Alfa Bank Proxy Interceptor loaded');

    // Перехватываем XMLHttpRequest
    const originalXHR = window.XMLHttpRequest;
    const proxyUrl = '/proxy-payment.php';

    window.XMLHttpRequest = function() {
        const xhr = new originalXHR();
        const originalOpen = xhr.open;
        const originalSend = xhr.send;

        xhr.open = function(method, url, ...args) {
            console.log('XHR Open:', method, url);
            
            // Если это запрос к Альфа-Банку, перенаправляем через прокси
            if (url && url.includes('alfabank.ru')) {
                console.log('🔄 Redirecting Alfa Bank request to proxy');
                url = proxyUrl;
                
                // Сохраняем оригинальный URL в заголовке
                this._originalAlfaUrl = arguments[1];
            }
            
            return originalOpen.call(this, method, url, ...args);
        };

        xhr.send = function(data) {
            console.log('XHR Send:', data);
            
            // Если это данные для Альфа-Банка, добавляем оригинальный URL
            if (this._originalAlfaUrl && data) {
                try {
                    const jsonData = typeof data === 'string' ? JSON.parse(data) : data;
                    jsonData._originalUrl = this._originalAlfaUrl;
                    data = JSON.stringify(jsonData);
                    console.log('✅ Added original URL to request');
                } catch (e) {
                    console.warn('Could not modify request data:', e);
                }
            }
            
            return originalSend.call(this, data);
        };

        return xhr;
    };

    // Перехватываем Fetch API
    const originalFetch = window.fetch;
    window.fetch = function(resource, options = {}) {
        let url = resource;
        
        if (typeof resource === 'string' && resource.includes('alfabank.ru')) {
            console.log('🔄 Intercepted Fetch request to Alfa Bank');
            url = proxyUrl;
            
            // Добавляем оригинальный URL в заголовки
            const modifiedOptions = {
                ...options,
                headers: {
                    ...options.headers,
                    'X-Original-URL': resource
                }
            };
            
            console.log('✅ Redirecting fetch to proxy');
            return originalFetch.call(this, url, modifiedOptions);
        }
        
        return originalFetch.call(this, resource, options);
    };

    // Мониторим динамически созданные скрипты
    const originalCreateElement = document.createElement;
    document.createElement = function(tagName) {
        const element = originalCreateElement.call(this, tagName);
        
        if (tagName.toLowerCase() === 'script') {
            const originalSrcDescriptor = Object.getOwnPropertyDescriptor(element, 'src');
            
            Object.defineProperty(element, 'src', {
                get: function() {
                    return originalSrcDescriptor.get.call(this);
                },
                set: function(value) {
                    console.log('Script src set:', value);
                    
                    // Если это скрипт Альфа-Банка, можем добавить логику
                    if (value && value.includes('alfabank.ru')) {
                        console.log('🔧 Alfa Bank script loaded');
                    }
                    
                    return originalSrcDescriptor.set.call(this, value);
                }
            });
        }
        
        return element;
    };

    console.log('✅ Alfa Bank Proxy Interceptor activated');
})();