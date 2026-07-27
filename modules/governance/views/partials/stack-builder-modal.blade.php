<dialog
    class="stack-builder-modal bn-shared-modal"
    data-governance-stack-builder
    data-shared-modal
>
    <form method="dialog" class="stack-builder-modal__sheet">
        <header class="stack-builder-modal__header">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="Custom Stack" data-text-en="Custom stack">Custom stack</p>
                <h2 data-text-de="Eigenen Stack bauen" data-text-en="Build your stack">Build your stack</h2>
            </div>
            <button type="submit" class="governance-hub__button governance-hub__button--compact" value="cancel" data-text-de="Schließen" data-text-en="Close">Close</button>
        </header>
        <div data-stack-builder-root data-stack-builder-mode="modal"></div>
        <div class="stack-builder-modal__library" data-stack-builder-library>
            <label class="stack-builder-modal__library-load">
                <span data-text-de="Gespeicherten Stack laden" data-text-en="Load saved stack">Load saved stack</span>
                <select data-stack-builder-load>
                    <option value="" data-text-de="— Auswählen —" data-text-en="— Choose —">— Choose —</option>
                </select>
            </label>
            <p class="stack-builder-modal__library-status" data-stack-builder-status hidden></p>
        </div>
        <footer class="stack-builder-modal__footer">
            <a class="governance-hub__button" href="{{ locale_route('tools.custom-stack-builder') }}">
                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                <span data-text-de="Als Tool öffnen" data-text-en="Open as tool">Open as tool</span>
            </a>
            <div class="stack-builder-modal__footer-actions">
                <button type="button" class="governance-hub__button" data-governance-stack-builder-save-as data-text-de="Als Stack speichern" data-text-en="Save as stack">Save as stack</button>
                <button type="submit" class="governance-hub__button governance-hub__button--primary" value="save" data-governance-stack-builder-save data-text-de="Übernehmen" data-text-en="Apply">Apply</button>
            </div>
        </footer>
    </form>
</dialog>
