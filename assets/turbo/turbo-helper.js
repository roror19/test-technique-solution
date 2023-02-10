const TurboHelper = class {
    constructor() {
        document.addEventListener('turbo:visit', () => {
            document.body.classList.add('turbo-loading');
        });
        document.addEventListener('turbo:before-render', (event) => {
            event.detail.newBody.classList.add('turbo-loading');
        });
        document.addEventListener('turbo:render', () => {
            setTimeout(() => {
                document.body.classList.remove('turbo-loading');
            }, 300);
        });
    }
}
export default new TurboHelper();