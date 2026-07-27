/**
 * Warn on tab close / reload when ephemeral discovery content would be lost.
 * @param {() => boolean} hasUnsavedContent
 * @param {() => string} getMessage
 */
export function bindLeaveGuard(hasUnsavedContent, getMessage) {
    /** @param {BeforeUnloadEvent} event */
    function onBeforeUnload(event) {
        if (!hasUnsavedContent()) return;
        event.preventDefault();
        // Chromium ignores custom strings; still set returnValue for older browsers.
        event.returnValue = getMessage();
        return event.returnValue;
    }

    window.addEventListener('beforeunload', onBeforeUnload);

    return () => {
        window.removeEventListener('beforeunload', onBeforeUnload);
    };
}
