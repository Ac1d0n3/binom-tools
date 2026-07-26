<?php

namespace Tests\Feature\Governance;

use Tests\TestCase;

class GovernanceHubTest extends TestCase
{
    public function test_governance_hub_connects_existing_hubs_and_tools(): void
    {
        $response = $this->get('/governance');

        $response->assertOk();
        $response->assertSee('Governance Hub');
        $response->assertSee('governance-hub', false);
        $response->assertSee('Governance control hub');
        $response->assertSee('data-governance-advisor', false);
        $response->assertSee('data-governance-advisor-config', false);
        $response->assertSee('Interactive decision aid');
        $response->assertSee('Ich baue neu auf', false);
        $response->assertSee('Ich ergänze Bestehendes', false);
        $response->assertSee('Alles ist da, ich brauche Hilfe', false);
        $response->assertSee('Source type');
        $response->assertSee('Target stack');
        $response->assertSee('Next question');
        $response->assertSee('Refine DQ decision');
        $response->assertSee('DQ goal');
        $response->assertSee('Issue class');
        $response->assertSee('governance-advisor__helpbox', false);
        $response->assertSee('<details class="governance-advisor__helpbox" open>', false);
        $response->assertSee('So nutzt du den Advisor', false);
        $response->assertSee('Der Governance Hub ist keine Linkliste', false);
        $response->assertSee('Plan-Aufgabe', false);
        $response->assertSee('Zusammenspiel der Tools', false);
        $response->assertSee('Jedes Tool kann allein genutzt werden', false);
        $response->assertSee('governance-advisor__save-disclosure', false);
        $response->assertSee('Session speichern oder Demo anlegen', false);
        $response->assertSee('Beispiel-Report ansehen', false);
        $response->assertSee('/governance/demo-report', false);
        $response->assertSee('data-governance-view-report', false);
        $response->assertSee('Report ansehen', false);
        $response->assertSee('governance-advisor__save-primary', false);
        $response->assertSee('governance-advisor__save-secondary', false);
        $response->assertSee('Collect infos workflow');
        $response->assertSee('Entscheidungshilfen', false);
        $response->assertSee('Decision question');
        $response->assertSee('Helps with');
        $response->assertSee('Afterwards you have');
        $response->assertSee('Shortlist');
        $response->assertSee('Source scope');
        $response->assertSee('fact/dimension candidates');
        $response->assertSee('Risk backlog');
        $response->assertSee('Source Scope Builder');
        $response->assertSee('Governance Stack Advisor');
        $response->assertSee('PII/DSDR Readiness');
        $response->assertSee('KPI Definition Card');
        $response->assertSee('Supplier library');
        $response->assertSee('Supplier Library: wofür hilft sie?', false);
        $response->assertSee('CRM &amp; revenue', false);
        $response->assertSee('ERP, finance &amp; procurement', false);
        $response->assertSee('HCM &amp; workforce', false);
        $response->assertSee('Collaboration &amp; service', false);
        $response->assertSee('PII Policy');
        $response->assertSee('/tools/kpi-requirements-intake', false);
        $response->assertSee('/tools/source-scope-builder', false);
        $response->assertSee('/tools/mart-design-brief-generator', false);
        $response->assertSee('/tools/governance-stack-advisor', false);
        $response->assertSee('/tools/pii-dsdr-readiness-checker', false);
        $response->assertSee('/tools/decision-brief-generator', false);
        $response->assertSee('/tools/vendor-learning-path-builder', false);
        $response->assertSee('/tools/architecture-fit', false);
        $response->assertSee('/tools/meta-export-generator', false);
        $response->assertSee('/tools/dbt-dq-rules-generator', false);
        $response->assertSee('/tools/dbt-dq-macro-generator', false);
        $response->assertSee('/tools/dbt-dq-history-generator', false);
        $response->assertSee('/tools/fabric-pii-governance-pattern-generator', false);
        $response->assertSee('/tools/databricks-pii-governance-pattern-generator', false);
        $response->assertSee('/tools/unity-catalog-governance-generator', false);
        $response->assertSee('/resources', false);
        $response->assertSee('/suppliers', false);
        $response->assertSee('/tools/stakeholder-matrix', false);
        $response->assertSee('/tools/kpi-definition', false);
        $response->assertSee('/tools/pii-policy-generator', false);
        $response->assertSee('/compliance', false);
        $response->assertSee('data-text-de="Governance"', false);
        $response->assertSee('tools-sidenav__link--active', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@context":"https://schema.org"', false);
        $response->assertDontSee('__contextArgs', false);
        $response->assertSee('Thomas Lindackers');
    }
}
