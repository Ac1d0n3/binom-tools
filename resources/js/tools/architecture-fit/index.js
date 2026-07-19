import '../../../css/tools/discovery-canvas.css';
import { mountChecklistCanvas } from '../discovery-shared/checklist-canvas.js';
import { createLabelApi, mergeDiscoveryLabels } from '../discovery-shared/labels.js';

const labels = mergeDiscoveryLabels({
    de: {
        'architectureFit.pageTitle': 'Architecture Fit Checklist',
        'architectureFit.pageLead':
            'Geführte Diagnose entlang Quellen → Integration → Transform → Consumption. Offene Punkte werden zur Engpass-Liste.',
        'architectureFit.howto.intro':
            'Für Woche 8: Architektur und Tools prüfen, ohne Bronze/Silver/Gold neu zu erfinden.',
        'architectureFit.howto.step1': 'Jeden Prüfpunkt beantworten und kurze Notizen festhalten.',
        'architectureFit.howto.step2': 'Erledigte Punkte abhaken; offene bleiben Engpässe.',
        'architectureFit.howto.step3': 'Markdown kopieren oder herunterladen — für Notizen, Wiki oder den Sprint Planner.',
        'architectureFit.howto.tip':
            'Nichts wird im Tool gespeichert. Ergebnis übernehmen (Copy/Download); Persistenz liegt bei dir (Doku/Plan).',
        'architectureFit.notePlaceholder': 'Notiz / Beleg…',
        'architectureFit.bottlenecks': 'Engpass-Liste (offen)',
        'architectureFit.section.sources': 'Quellen',
        'architectureFit.section.integration': 'Integration / Plattform',
        'architectureFit.section.transform': 'Transformation',
        'architectureFit.section.consumption': 'Consumption / BI',
        'architectureFit.item.sourcesMapped': 'Prioritätsquellen und Owner sind benannt',
        'architectureFit.item.sourcesMapped.help': 'ERP/CRM/Files/APIs mit System-Owner und Zugriffspfad.',
        'architectureFit.item.interfacesKnown': 'Extraktionswege und Latenz sind dokumentiert',
        'architectureFit.item.interfacesKnown.help': 'API, CDC, Batch, Datei — inkl. Fehlermodi.',
        'architectureFit.item.platformChosen': 'Plattformrollen sind bewusst (nicht historisch gewachsen)',
        'architectureFit.item.platformChosen.help': 'Warehouse/Lakehouse vs. Satelliten klar getrennt.',
        'architectureFit.item.hostingFit': 'Hosting-Grenzen (Cloud/Self-Hosted) sind bewertet',
        'architectureFit.item.hostingFit.help': 'Kosten, Latenz, Skills und Compliance berücksichtigt.',
        'architectureFit.item.transformsOwned': 'Transform-Layer hat klare Ownership',
        'architectureFit.item.transformsOwned.help': 'dbt/Jobs/Pipelines mit verantwortlicher Person/Team.',
        'architectureFit.item.testsPresent': 'Kritische Pfade haben Tests oder messbare DQ',
        'architectureFit.item.testsPresent.help': 'Nicht nur Anekdoten — Symptome und Tests trennen.',
        'architectureFit.item.semanticsClear': 'Semantik/KPI-Definitionen sind nicht nur in BI-Tools',
        'architectureFit.item.semanticsClear.help': 'Vermeide doppelte Measures ohne Warehouse-Definition.',
        'architectureFit.item.biSprawl': 'BI-Tool-Wildwuchs und Schatten-Reports sind erfasst',
        'architectureFit.item.biSprawl.help': 'Eine Geschäftsfrage, mehrere Engines — bewusst priorisieren.',
        'architectureFit.item.simplestViable': 'Zielbild folgt der einfachsten tragfähigen Form',
        'architectureFit.item.simplestViable.help': 'Kein Bronze/Silver/Gold ohne Geschäftsfrage.',
        'architectureFit.item.pilotPath': 'Ein verbesserbarer Pilot-Pfad ist isolierbar',
        'architectureFit.item.pilotPath.help': 'Nicht alles neu schreiben — einen Flow beweisbar machen.',
        'discovery.exportTitle': 'Architekturdiagnose',
    },
    en: {
        'architectureFit.pageTitle': 'Architecture Fit Checklist',
        'architectureFit.pageLead':
            'Guided diagnosis along sources → integration → transform → consumption. Open items become the bottleneck list.',
        'architectureFit.howto.intro':
            'For week 8: review architecture and tools without reinventing bronze/silver/gold.',
        'architectureFit.howto.step1': 'Answer each check and capture short notes.',
        'architectureFit.howto.step2': 'Tick completed items; open ones stay bottlenecks.',
        'architectureFit.howto.step3': 'Copy or download Markdown — for notes, wiki, or the Sprint Planner.',
        'architectureFit.howto.tip':
            'Nothing is stored in the tool. Transfer the result (copy/download); persistence is yours (docs/plan).',
        'architectureFit.notePlaceholder': 'Note / evidence…',
        'architectureFit.bottlenecks': 'Bottleneck list (open)',
        'architectureFit.section.sources': 'Sources',
        'architectureFit.section.integration': 'Integration / platform',
        'architectureFit.section.transform': 'Transformation',
        'architectureFit.section.consumption': 'Consumption / BI',
        'architectureFit.item.sourcesMapped': 'Priority sources and owners are named',
        'architectureFit.item.sourcesMapped.help': 'ERP/CRM/files/APIs with system owner and access path.',
        'architectureFit.item.interfacesKnown': 'Extraction paths and latency are documented',
        'architectureFit.item.interfacesKnown.help': 'API, CDC, batch, file — including failure modes.',
        'architectureFit.item.platformChosen': 'Platform roles are intentional (not accidental growth)',
        'architectureFit.item.platformChosen.help': 'Warehouse/lakehouse vs satellites clearly separated.',
        'architectureFit.item.hostingFit': 'Hosting constraints (cloud/self-hosted) are assessed',
        'architectureFit.item.hostingFit.help': 'Cost, latency, skills, and compliance considered.',
        'architectureFit.item.transformsOwned': 'Transform layer has clear ownership',
        'architectureFit.item.transformsOwned.help': 'dbt/jobs/pipelines with a responsible person/team.',
        'architectureFit.item.testsPresent': 'Critical paths have tests or measurable DQ',
        'architectureFit.item.testsPresent.help': 'Separate symptoms and tests from anecdotes.',
        'architectureFit.item.semanticsClear': 'Semantics/KPI definitions are not only in BI tools',
        'architectureFit.item.semanticsClear.help': 'Avoid duplicate measures without a warehouse definition.',
        'architectureFit.item.biSprawl': 'BI tool sprawl and shadow reports are captured',
        'architectureFit.item.biSprawl.help': 'One business question, many engines — prioritize deliberately.',
        'architectureFit.item.simplestViable': 'Target picture follows the simplest viable form',
        'architectureFit.item.simplestViable.help': 'No bronze/silver/gold without a business question.',
        'architectureFit.item.pilotPath': 'An improvable pilot path can be isolated',
        'architectureFit.item.pilotPath.help': 'Do not rewrite everything — prove one flow.',
        'discovery.exportTitle': 'Architecture diagnosis',
    },
});

const { t, applyLabels } = createLabelApi(labels);

const app = document.getElementById('architecture-fit-app');
if (!app) {
    throw new Error('Architecture fit root not found');
}

mountChecklistCanvas({
    root: app,
    legacyStorageKeys: ['bn-tools:architecture-fit:v1'],
    t,
    applyLabels,
    sections: [
        {
            id: 'sources',
            titleKey: 'architectureFit.section.sources',
            items: [
                {
                    id: 'sourcesMapped',
                    labelKey: 'architectureFit.item.sourcesMapped',
                    helpKey: 'architectureFit.item.sourcesMapped.help',
                },
                {
                    id: 'interfacesKnown',
                    labelKey: 'architectureFit.item.interfacesKnown',
                    helpKey: 'architectureFit.item.interfacesKnown.help',
                },
            ],
        },
        {
            id: 'integration',
            titleKey: 'architectureFit.section.integration',
            items: [
                {
                    id: 'platformChosen',
                    labelKey: 'architectureFit.item.platformChosen',
                    helpKey: 'architectureFit.item.platformChosen.help',
                },
                {
                    id: 'hostingFit',
                    labelKey: 'architectureFit.item.hostingFit',
                    helpKey: 'architectureFit.item.hostingFit.help',
                },
            ],
        },
        {
            id: 'transform',
            titleKey: 'architectureFit.section.transform',
            items: [
                {
                    id: 'transformsOwned',
                    labelKey: 'architectureFit.item.transformsOwned',
                    helpKey: 'architectureFit.item.transformsOwned.help',
                },
                {
                    id: 'testsPresent',
                    labelKey: 'architectureFit.item.testsPresent',
                    helpKey: 'architectureFit.item.testsPresent.help',
                },
            ],
        },
        {
            id: 'consumption',
            titleKey: 'architectureFit.section.consumption',
            items: [
                {
                    id: 'semanticsClear',
                    labelKey: 'architectureFit.item.semanticsClear',
                    helpKey: 'architectureFit.item.semanticsClear.help',
                },
                {
                    id: 'biSprawl',
                    labelKey: 'architectureFit.item.biSprawl',
                    helpKey: 'architectureFit.item.biSprawl.help',
                },
                {
                    id: 'simplestViable',
                    labelKey: 'architectureFit.item.simplestViable',
                    helpKey: 'architectureFit.item.simplestViable.help',
                },
                {
                    id: 'pilotPath',
                    labelKey: 'architectureFit.item.pilotPath',
                    helpKey: 'architectureFit.item.pilotPath.help',
                },
            ],
        },
    ],
});
