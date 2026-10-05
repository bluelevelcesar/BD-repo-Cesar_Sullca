function copyCode(button) {
    const codeBlock = button.closest('.print-code-block').querySelector('code');
    const textToCopy = codeBlock.textContent;

    navigator.clipboard.writeText(textToCopy).then(() => {
        const originalText = button.textContent;
        button.textContent = '[✓ COPIADO]';
        button.style.background = '#d92b2b';
        button.style.color = '#ffffff';

        setTimeout(() => {
            button.textContent = originalText;
            button.style.background = '';
            button.style.color = '';
        }, 2000);
    }).catch(err => {
        console.error('Error al copiar el código:', err);
    });
}