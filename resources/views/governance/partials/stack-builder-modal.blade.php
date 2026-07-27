<dialog class="stack-builder-modal" data-governance-stack-builder>
    <form method="dialog" class="stack-builder-modal__sheet">
        <header class="stack-builder-modal__header">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="Custom Stack" data-text-en="Custom stack">Custom stack</p>
                <h2 data-text-de="Eigenen Stack bauen" data-text-en="Build your stack">Build your stack</h2>
            </div>
            <button type="submit" class="governance-hub__button governance-hub__button--compact" value="cancel" data-text-de="Schließen" data-text-en="Close">Close</button>
        </header>
        <div data-stack-builder-root data-stack-builder-mode="modal"></div>
        <footer class="stack-builder-modal__footer">
            <a class="governance-hub__button" href="{{ locale_route('tools.custom-stack-builder') }}">
                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                <span data-text-de="Als Tool öffnen" data-text-en="Open as tool">Open as tool</span>
            </a>
            <button type="submit" class="governance-hub__button governance-hub__button--primary" value="save" data-governance-stack-builder-save data-text-de="Übernehmen" data-text-en="Apply">Apply</button>
        </footer>
    </form>
</dialog>
