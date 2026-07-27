import { describe, expect, it, beforeEach } from 'vitest';
import {
    __resetRadarReadStoreForTests,
    clearAllRadarRead,
    hasAnyRadarRead,
    isRadarItemRead,
    markRadarItemRead,
    readRadarHideRead,
    toggleRadarItemRead,
    unmarkRadarItemRead,
    writeRadarHideRead,
} from './radar-read-state.js';

describe('radar-read-state', () => {
    beforeEach(() => {
        __resetRadarReadStoreForTests();
    });

    it('marks and unmarks items as read', () => {
        expect(isRadarItemRead('item-a')).toBe(false);
        expect(markRadarItemRead('item-a')).toBe(true);
        expect(isRadarItemRead('item-a')).toBe(true);
        expect(markRadarItemRead('item-a')).toBe(false);
        expect(unmarkRadarItemRead('item-a')).toBe(true);
        expect(isRadarItemRead('item-a')).toBe(false);
    });

    it('toggles read state', () => {
        expect(toggleRadarItemRead('item-b')).toBe(true);
        expect(isRadarItemRead('item-b')).toBe(true);
        expect(toggleRadarItemRead('item-b')).toBe(false);
        expect(isRadarItemRead('item-b')).toBe(false);
    });

    it('defaults hide-read to on and clears markers', () => {
        expect(readRadarHideRead()).toBe(true);
        writeRadarHideRead(false);
        expect(readRadarHideRead()).toBe(false);
        writeRadarHideRead(true);
        expect(readRadarHideRead()).toBe(true);

        markRadarItemRead('item-c');
        expect(hasAnyRadarRead()).toBe(true);
        expect(clearAllRadarRead()).toBe(true);
        expect(hasAnyRadarRead()).toBe(false);
        expect(isRadarItemRead('item-c')).toBe(false);
    });
});
