<?php

/**
 * Curated vendor help, governance, learning and certification links for /resources.
 */
return [
    'vendors' => [
        'microsoft' => ['de' => 'Microsoft', 'en' => 'Microsoft'],
        'amazon' => ['de' => 'Amazon', 'en' => 'Amazon'],
        'google' => ['de' => 'Google', 'en' => 'Google'],
        'salesforce' => ['de' => 'Salesforce', 'en' => 'Salesforce'],
        'sap' => ['de' => 'SAP', 'en' => 'SAP'],
        'snowflake' => ['de' => 'Snowflake', 'en' => 'Snowflake'],
        'databricks' => ['de' => 'Databricks', 'en' => 'Databricks'],
        'dbt' => ['de' => 'dbt Labs', 'en' => 'dbt Labs'],
        'fivetran' => ['de' => 'Fivetran', 'en' => 'Fivetran'],
        'qlik' => ['de' => 'Qlik', 'en' => 'Qlik'],
        'tableau' => ['de' => 'Tableau / Salesforce', 'en' => 'Tableau / Salesforce'],
        'atlan' => ['de' => 'Atlan', 'en' => 'Atlan'],
        'collibra' => ['de' => 'Collibra', 'en' => 'Collibra'],
        'alation' => ['de' => 'Alation', 'en' => 'Alation'],
        'openai' => ['de' => 'OpenAI', 'en' => 'OpenAI'],
        'anthropic' => ['de' => 'Anthropic', 'en' => 'Anthropic'],
        'cursor' => ['de' => 'Cursor', 'en' => 'Cursor'],
        'github' => ['de' => 'GitHub', 'en' => 'GitHub'],
        'ovh' => ['de' => 'OVHcloud', 'en' => 'OVHcloud'],
        'hetzner' => ['de' => 'Hetzner', 'en' => 'Hetzner'],
        'metabase' => ['de' => 'Metabase', 'en' => 'Metabase'],
        'apache' => ['de' => 'Apache', 'en' => 'Apache'],
        'lightdash' => ['de' => 'Lightdash', 'en' => 'Lightdash'],
        'openmetadata' => ['de' => 'OpenMetadata', 'en' => 'OpenMetadata'],
        'acryl' => ['de' => 'Acryl / DataHub', 'en' => 'Acryl / DataHub'],
        'openlineage' => ['de' => 'OpenLineage', 'en' => 'OpenLineage'],
        'marquez' => ['de' => 'Marquez', 'en' => 'Marquez'],
        'miro' => ['de' => 'Miro', 'en' => 'Miro'],
        'talend' => ['de' => 'Talend (Qlik)', 'en' => 'Talend (Qlik)'],
    ],

    'families' => [
        'platforms' => ['de' => 'Plattformen', 'en' => 'Platforms'],
        'cloud' => ['de' => 'Cloud Hosting', 'en' => 'Cloud hosting'],
        'transformation' => ['de' => 'Transformation', 'en' => 'Transformation'],
        'bi' => ['de' => 'BI', 'en' => 'BI'],
        'catalogs' => ['de' => 'Catalogs', 'en' => 'Catalogs'],
        'lineage' => ['de' => 'Lineage', 'en' => 'Lineage'],
        'ai' => ['de' => 'AI', 'en' => 'AI'],
        'planning' => ['de' => 'Planung', 'en' => 'Planning'],
    ],

    'products' => [
        [
            'id' => 'aws',
            'family' => 'cloud',
            'vendor' => 'amazon',
            'label' => ['de' => 'Amazon Web Services', 'en' => 'Amazon Web Services'],
            'purpose' => ['de' => 'Cloud Infrastructure (IaaS/PaaS)', 'en' => 'Cloud infrastructure (IaaS/PaaS)'],
            'models' => ['saas'],
            'brandColor' => '#FF9900',
            'logo' => 'images/aws-badge.svg',
            'residency' => ['eu', 'de', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'c5',
                        'label' => ['de' => 'BSI C5', 'en' => 'BSI C5'],
                        'href' => 'https://aws.amazon.com/compliance/bsi-c5/',
                        'description' => ['de' => 'Wichtig für deutsche Behörden/Cloud-Akteure.', 'en' => 'Important for German public-sector cloud use.'],
                    ],
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://aws.amazon.com/compliance/iso-27001-faqs/',
                        'description' => ['de' => 'ISO/IEC 27001.', 'en' => 'ISO/IEC 27001.'],
                    ],
                    [
                        'id' => 'soc2',
                        'label' => ['de' => 'SOC 2', 'en' => 'SOC 2'],
                        'href' => 'https://aws.amazon.com/compliance/soc-faqs/',
                        'description' => ['de' => 'SOC 1/2/3 Reports.', 'en' => 'SOC 1/2/3 reports.'],
                    ],
                    [
                        'id' => 'pci',
                        'label' => ['de' => 'PCI DSS', 'en' => 'PCI DSS'],
                        'href' => 'https://aws.amazon.com/compliance/pci-dss-level-1-faqs/',
                        'description' => ['de' => 'Relevant für Banken/Payments.', 'en' => 'Relevant for banking/payments.'],
                    ],
                    [
                        'id' => 'gdpr',
                        'label' => ['de' => 'DSGVO / GDPR', 'en' => 'GDPR'],
                        'href' => 'https://aws.amazon.com/compliance/gdpr-center/',
                        'description' => ['de' => 'DSGVO-Center und AVV.', 'en' => 'GDPR center and DPA.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'AWS Documentation', 'en' => 'AWS documentation'],
                        'href' => 'https://docs.aws.amazon.com/',
                        'description' => ['de' => 'Offizielle AWS Docs.', 'en' => 'Official AWS docs.'],
                    ],
                    [
                        'label' => ['de' => 'Regions & AZs', 'en' => 'Regions & AZs'],
                        'href' => 'https://aws.amazon.com/about-aws/global-infrastructure/regions_az/',
                        'description' => ['de' => 'Serverstandorte weltweit inkl. EU/Frankfurt.', 'en' => 'Global server locations incl. EU/Frankfurt.'],
                    ],
                    [
                        'label' => ['de' => 'AWS in Europe', 'en' => 'AWS in Europe'],
                        'href' => 'https://aws.amazon.com/compliance/eu-digital-sovereignty/',
                        'description' => ['de' => 'EU Digital Sovereignty.', 'en' => 'EU digital sovereignty.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'AWS Artifact', 'en' => 'AWS Artifact'],
                        'href' => 'https://aws.amazon.com/artifact/',
                        'description' => ['de' => 'Compliance-Reports herunterladen.', 'en' => 'Download compliance reports.'],
                    ],
                    [
                        'label' => ['de' => 'IAM Best Practices', 'en' => 'IAM best practices'],
                        'href' => 'https://docs.aws.amazon.com/IAM/latest/UserGuide/best-practices.html',
                        'description' => ['de' => 'Identitäten und Zugriffe.', 'en' => 'Identities and access.'],
                    ],
                    [
                        'label' => ['de' => 'Well-Architected Security', 'en' => 'Well-Architected Security'],
                        'href' => 'https://docs.aws.amazon.com/wellarchitected/latest/security-pillar/welcome.html',
                        'description' => ['de' => 'Security Pillar.', 'en' => 'Security pillar.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'AWS Skill Builder', 'en' => 'AWS Skill Builder'],
                        'href' => 'https://skillbuilder.aws/',
                        'description' => ['de' => 'Kurse und Lernpfade.', 'en' => 'Courses and learning paths.'],
                    ],
                    [
                        'label' => ['de' => 'AWS Workshops', 'en' => 'AWS Workshops'],
                        'href' => 'https://workshops.aws/',
                        'description' => ['de' => 'Hands-on Workshops.', 'en' => 'Hands-on workshops.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'AWS Certifications', 'en' => 'AWS Certifications'],
                        'href' => 'https://aws.amazon.com/certification/',
                        'description' => ['de' => 'Cloud-Zertifizierungen.', 'en' => 'Cloud certifications.'],
                    ],
            ],
        ],
        [
            'id' => 'azure',
            'family' => 'cloud',
            'vendor' => 'microsoft',
            'label' => ['de' => 'Microsoft Azure', 'en' => 'Microsoft Azure'],
            'purpose' => ['de' => 'Cloud Infrastructure (IaaS/PaaS)', 'en' => 'Cloud infrastructure (IaaS/PaaS)'],
            'models' => ['saas'],
            'brandColor' => '#0078D4',
            'logo' => 'images/azure-badge.svg',
            'residency' => ['eu', 'de', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'c5',
                        'label' => ['de' => 'BSI C5', 'en' => 'BSI C5'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-c5',
                        'description' => ['de' => 'BSI C5 für Azure — Behördenrelevant.', 'en' => 'BSI C5 for Azure — public-sector relevant.'],
                    ],
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-ISO-27001',
                        'description' => ['de' => 'ISO/IEC 27001.', 'en' => 'ISO/IEC 27001.'],
                    ],
                    [
                        'id' => 'soc2',
                        'label' => ['de' => 'SOC 2', 'en' => 'SOC 2'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-SOC',
                        'description' => ['de' => 'SOC-Reports.', 'en' => 'SOC reports.'],
                    ],
                    [
                        'id' => 'pci',
                        'label' => ['de' => 'PCI DSS', 'en' => 'PCI DSS'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-PCI-DSS',
                        'description' => ['de' => 'Payments / Banken.', 'en' => 'Payments / banking.'],
                    ],
                    [
                        'id' => 'gdpr',
                        'label' => ['de' => 'DSGVO / GDPR', 'en' => 'GDPR'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/gdpr',
                        'description' => ['de' => 'DSGVO-Guidance und DPA.', 'en' => 'GDPR guidance and DPA.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Azure Documentation', 'en' => 'Azure documentation'],
                        'href' => 'https://learn.microsoft.com/en-us/azure/',
                        'description' => ['de' => 'Offizielle Azure Docs.', 'en' => 'Official Azure docs.'],
                    ],
                    [
                        'label' => ['de' => 'Azure Geographies', 'en' => 'Azure geographies'],
                        'href' => 'https://azure.microsoft.com/en-us/explore/global-infrastructure/geographies/',
                        'description' => ['de' => 'Regionen inkl. Germany West Central.', 'en' => 'Regions incl. Germany West Central.'],
                    ],
                    [
                        'label' => ['de' => 'EU Data Boundary', 'en' => 'EU Data Boundary'],
                        'href' => 'https://learn.microsoft.com/en-us/privacy/eudb/eu-data-boundary-learn',
                        'description' => ['de' => 'EU-Datengrenze.', 'en' => 'EU data boundary.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Service Trust Portal', 'en' => 'Service Trust Portal'],
                        'href' => 'https://servicetrust.microsoft.com/',
                        'description' => ['de' => 'Compliance-Berichte und Audits.', 'en' => 'Compliance reports and audits.'],
                    ],
                    [
                        'label' => ['de' => 'Azure Policy', 'en' => 'Azure Policy'],
                        'href' => 'https://learn.microsoft.com/en-us/azure/governance/policy/overview',
                        'description' => ['de' => 'Guardrails und Governance.', 'en' => 'Guardrails and governance.'],
                    ],
                    [
                        'label' => ['de' => 'Microsoft Purview', 'en' => 'Microsoft Purview'],
                        'href' => 'https://learn.microsoft.com/en-us/purview/',
                        'description' => ['de' => 'Data Governance in Azure.', 'en' => 'Data governance in Azure.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Microsoft Learn Azure', 'en' => 'Microsoft Learn Azure'],
                        'href' => 'https://learn.microsoft.com/en-us/training/azure/',
                        'description' => ['de' => 'Lernpfade für Azure.', 'en' => 'Learning paths for Azure.'],
                    ],
                    [
                        'label' => ['de' => 'Cloud Adoption Framework', 'en' => 'Cloud Adoption Framework'],
                        'href' => 'https://learn.microsoft.com/en-us/azure/cloud-adoption-framework/',
                        'description' => ['de' => 'Adoption und Landing Zones.', 'en' => 'Adoption and landing zones.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Azure Certifications', 'en' => 'Azure certifications'],
                        'href' => 'https://learn.microsoft.com/en-us/credentials/certifications/browse/?products=azure',
                        'description' => ['de' => 'AZ-900, AZ-104, Security usw.', 'en' => 'AZ-900, AZ-104, Security, etc.'],
                    ],
            ],
        ],
        [
            'id' => 'gcp',
            'family' => 'cloud',
            'vendor' => 'google',
            'label' => ['de' => 'Google Cloud', 'en' => 'Google Cloud'],
            'purpose' => ['de' => 'Cloud Infrastructure (IaaS/PaaS)', 'en' => 'Cloud infrastructure (IaaS/PaaS)'],
            'models' => ['saas'],
            'brandColor' => '#4285F4',
            'logo' => 'images/gcp-badge.svg',
            'residency' => ['eu', 'de', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'c5',
                        'label' => ['de' => 'BSI C5', 'en' => 'BSI C5'],
                        'href' => 'https://cloud.google.com/security/compliance/c5',
                        'description' => ['de' => 'BSI C5 Attestation.', 'en' => 'BSI C5 attestation.'],
                    ],
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://cloud.google.com/security/compliance/iso-27001',
                        'description' => ['de' => 'ISO/IEC 27001.', 'en' => 'ISO/IEC 27001.'],
                    ],
                    [
                        'id' => 'soc2',
                        'label' => ['de' => 'SOC 2', 'en' => 'SOC 2'],
                        'href' => 'https://cloud.google.com/security/compliance/soc-2',
                        'description' => ['de' => 'SOC 2/3.', 'en' => 'SOC 2/3.'],
                    ],
                    [
                        'id' => 'pci',
                        'label' => ['de' => 'PCI DSS', 'en' => 'PCI DSS'],
                        'href' => 'https://cloud.google.com/security/compliance/pci-dss',
                        'description' => ['de' => 'Payments-Compliance.', 'en' => 'Payments compliance.'],
                    ],
                    [
                        'id' => 'gdpr',
                        'label' => ['de' => 'DSGVO / GDPR', 'en' => 'GDPR'],
                        'href' => 'https://cloud.google.com/privacy/gdpr',
                        'description' => ['de' => 'DSGVO-Ressourcen.', 'en' => 'GDPR resources.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Google Cloud Docs', 'en' => 'Google Cloud docs'],
                        'href' => 'https://cloud.google.com/docs',
                        'description' => ['de' => 'Offizielle GCP Docs.', 'en' => 'Official GCP docs.'],
                    ],
                    [
                        'label' => ['de' => 'Cloud Locations', 'en' => 'Cloud locations'],
                        'href' => 'https://cloud.google.com/about/locations',
                        'description' => ['de' => 'Regionen inkl. frankfurt/europe-west3.', 'en' => 'Regions incl. frankfurt/europe-west3.'],
                    ],
                    [
                        'label' => ['de' => 'Sovereign Cloud options', 'en' => 'Sovereign cloud options'],
                        'href' => 'https://cloud.google.com/sovereign-cloud',
                        'description' => ['de' => 'Souveränitäts-Optionen.', 'en' => 'Sovereignty options.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Compliance Reports Manager', 'en' => 'Compliance Reports Manager'],
                        'href' => 'https://cloud.google.com/security/compliance/compliance-reports-manager',
                        'description' => ['de' => 'Audit-Reports.', 'en' => 'Audit reports.'],
                    ],
                    [
                        'label' => ['de' => 'IAM Overview', 'en' => 'IAM overview'],
                        'href' => 'https://cloud.google.com/iam/docs/overview',
                        'description' => ['de' => 'Identitäten und Berechtigungen.', 'en' => 'Identities and permissions.'],
                    ],
                    [
                        'label' => ['de' => 'Assured Workloads', 'en' => 'Assured Workloads'],
                        'href' => 'https://cloud.google.com/assured-workloads/docs/overview',
                        'description' => ['de' => 'Regulierte Workloads.', 'en' => 'Regulated workloads.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Google Cloud Skills Boost', 'en' => 'Google Cloud Skills Boost'],
                        'href' => 'https://www.cloudskillsboost.google/',
                        'description' => ['de' => 'Labs und Lernpfade.', 'en' => 'Labs and learning paths.'],
                    ],
                    [
                        'label' => ['de' => 'Architecture Center', 'en' => 'Architecture Center'],
                        'href' => 'https://cloud.google.com/architecture',
                        'description' => ['de' => 'Referenzarchitekturen.', 'en' => 'Reference architectures.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Google Cloud Certifications', 'en' => 'Google Cloud certifications'],
                        'href' => 'https://cloud.google.com/learn/certification',
                        'description' => ['de' => 'Cloud-Zertifizierungen.', 'en' => 'Cloud certifications.'],
                    ],
            ],
        ],
        [
            'id' => 'ovhcloud',
            'family' => 'cloud',
            'vendor' => 'ovh',
            'label' => ['de' => 'OVHcloud', 'en' => 'OVHcloud'],
            'purpose' => ['de' => 'EU Cloud Hosting', 'en' => 'EU cloud hosting'],
            'models' => ['saas'],
            'brandColor' => '#000E9C',
            'logo' => 'images/ovhcloud-badge.svg',
            'residency' => ['eu', 'de'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://www.ovhcloud.com/en/identity-security-certifications/',
                        'description' => ['de' => 'ISO-Zertifizierungen.', 'en' => 'ISO certifications.'],
                    ],
                    [
                        'id' => 'secnumcloud',
                        'label' => ['de' => 'SecNumCloud', 'en' => 'SecNumCloud'],
                        'href' => 'https://www.ovhcloud.com/en/identity-security-certifications/',
                        'description' => ['de' => 'FR Behörden-/Souveränitäts-Standard.', 'en' => 'FR public-sector sovereignty standard.'],
                    ],
                    [
                        'id' => 'hds',
                        'label' => ['de' => 'HDS', 'en' => 'HDS'],
                        'href' => 'https://www.ovhcloud.com/en/identity-security-certifications/',
                        'description' => ['de' => 'Health Data Hosting (FR).', 'en' => 'Health data hosting (FR).'],
                    ],
                    [
                        'id' => 'gdpr',
                        'label' => ['de' => 'DSGVO / GDPR', 'en' => 'GDPR'],
                        'href' => 'https://www.ovhcloud.com/en/personal-data-protection/',
                        'description' => ['de' => 'EU-Datenresidenz und DSGVO.', 'en' => 'EU data residency and GDPR.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'OVHcloud Docs', 'en' => 'OVHcloud docs'],
                        'href' => 'https://help.ovhcloud.com/',
                        'description' => ['de' => 'Hilfe und Dokumentation.', 'en' => 'Help and documentation.'],
                    ],
                    [
                        'label' => ['de' => 'Datacenter Locations', 'en' => 'Datacenter locations'],
                        'href' => 'https://www.ovhcloud.com/en/about-us/global-infrastructure/',
                        'description' => ['de' => 'EU-Standorte inkl. Deutschland.', 'en' => 'EU locations incl. Germany.'],
                    ],
                    [
                        'label' => ['de' => 'Public Cloud', 'en' => 'Public Cloud'],
                        'href' => 'https://www.ovhcloud.com/en/public-cloud/',
                        'description' => ['de' => 'Public-Cloud-Produkte.', 'en' => 'Public cloud products.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Trust Center', 'en' => 'Trust center'],
                        'href' => 'https://www.ovhcloud.com/en/identity-security-certifications/',
                        'description' => ['de' => 'Security und Zertifizierungen.', 'en' => 'Security and certifications.'],
                    ],
                    [
                        'label' => ['de' => 'Privacy Policy', 'en' => 'Privacy policy'],
                        'href' => 'https://www.ovhcloud.com/en/personal-data-protection/',
                        'description' => ['de' => 'Datenschutz.', 'en' => 'Privacy.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'OVHcloud Tutorials', 'en' => 'OVHcloud tutorials'],
                        'href' => 'https://help.ovhcloud.com/csm/en-tutorials?id=kb_browse_cat&kb_category=3f441f7f1bc26110a11d404fe10071c1',
                        'description' => ['de' => 'Tutorials und Guides.', 'en' => 'Tutorials and guides.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Certification Overview', 'en' => 'Certification overview'],
                        'href' => 'https://www.ovhcloud.com/en/identity-security-certifications/',
                        'description' => ['de' => 'Vendor-Compliance-Übersicht.', 'en' => 'Vendor compliance overview.'],
                    ],
            ],
        ],
        [
            'id' => 'hetzner',
            'family' => 'cloud',
            'vendor' => 'hetzner',
            'label' => ['de' => 'Hetzner', 'en' => 'Hetzner'],
            'purpose' => ['de' => 'EU/DE Cloud & Dedicated Hosting', 'en' => 'EU/DE cloud & dedicated hosting'],
            'models' => ['saas'],
            'brandColor' => '#D50C2D',
            'logo' => 'images/hetzner-badge.svg',
            'residency' => ['eu', 'de'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://www.hetzner.com/legal/iso-27001/',
                        'description' => ['de' => 'ISO 27001 zertifizierte Rechenzentren.', 'en' => 'ISO 27001 certified data centers.'],
                    ],
                    [
                        'id' => 'gdpr',
                        'label' => ['de' => 'DSGVO / GDPR', 'en' => 'GDPR'],
                        'href' => 'https://www.hetzner.com/legal/privacy-policy/',
                        'description' => ['de' => 'DE/EU Hosting, DSGVO-fokussiert.', 'en' => 'DE/EU hosting, GDPR-focused.'],
                    ],
                    [
                        'id' => 'soc2',
                        'label' => ['de' => 'SOC 2', 'en' => 'SOC 2'],
                        'href' => 'https://www.hetzner.com/legal/',
                        'description' => ['de' => 'Rechtliche / Security-Unterlagen.', 'en' => 'Legal / security materials.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Hetzner Docs', 'en' => 'Hetzner docs'],
                        'href' => 'https://docs.hetzner.com/',
                        'description' => ['de' => 'Offizielle Dokumentation.', 'en' => 'Official documentation.'],
                    ],
                    [
                        'label' => ['de' => 'Cloud Console', 'en' => 'Cloud console'],
                        'href' => 'https://docs.hetzner.com/cloud/',
                        'description' => ['de' => 'Cloud-Produkte und API.', 'en' => 'Cloud products and API.'],
                    ],
                    [
                        'label' => ['de' => 'Data Centers', 'en' => 'Data centers'],
                        'href' => 'https://www.hetzner.com/unternehmen/rechenzentrum/',
                        'description' => ['de' => 'Standorte in DE und FI.', 'en' => 'Locations in DE and FI.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'AVV / DPA', 'en' => 'DPA'],
                        'href' => 'https://www.hetzner.com/legal/terms-and-conditions/',
                        'description' => ['de' => 'Vertragliche Grundlagen / AV.', 'en' => 'Contractual basis / DPA.'],
                    ],
                    [
                        'label' => ['de' => 'Privacy Policy', 'en' => 'Privacy policy'],
                        'href' => 'https://www.hetzner.com/legal/privacy-policy/',
                        'description' => ['de' => 'Datenschutz.', 'en' => 'Privacy.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Community Tutorials', 'en' => 'Community tutorials'],
                        'href' => 'https://community.hetzner.com/tutorials',
                        'description' => ['de' => 'Community-Anleitungen.', 'en' => 'Community guides.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'ISO 27001 Info', 'en' => 'ISO 27001 info'],
                        'href' => 'https://www.hetzner.com/legal/iso-27001/',
                        'description' => ['de' => 'Zertifizierungsinformationen.', 'en' => 'Certification information.'],
                    ],
            ],
        ],
        [
            'id' => 'snowflake',
            'family' => 'platforms',
            'vendor' => 'snowflake',
            'label' => ['de' => 'Snowflake', 'en' => 'Snowflake'],
            'purpose' => ['de' => 'Cloud Data Warehouse', 'en' => 'Cloud data warehouse'],
            'models' => ['saas'],
            'brandColor' => '#29B5E8',
            'logo' => 'images/snowflake-badge.svg',
            'residency' => ['eu', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://www.snowflake.com/en/legal/security-overview/',
                        'description' => ['de' => 'ISMS-Zertifizierung.', 'en' => 'ISMS certification.'],
                    ],
                    [
                        'id' => 'soc2',
                        'label' => ['de' => 'SOC 2', 'en' => 'SOC 2'],
                        'href' => 'https://www.snowflake.com/en/legal/security-overview/',
                        'description' => ['de' => 'SOC 2 Type II.', 'en' => 'SOC 2 Type II.'],
                    ],
                    [
                        'id' => 'c5',
                        'label' => ['de' => 'BSI C5', 'en' => 'BSI C5'],
                        'href' => 'https://www.snowflake.com/en/legal/security-overview/',
                        'description' => ['de' => 'Relevant für dt. Behörden-/Cloud-Anforderungen.', 'en' => 'Relevant for German public-sector cloud requirements.'],
                    ],
                    [
                        'id' => 'pci',
                        'label' => ['de' => 'PCI DSS', 'en' => 'PCI DSS'],
                        'href' => 'https://www.snowflake.com/en/legal/security-overview/',
                        'description' => ['de' => 'Zahlungskarten-Compliance.', 'en' => 'Payment card compliance.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Documentation', 'en' => 'Documentation'],
                        'href' => 'https://docs.snowflake.com/',
                        'description' => ['de' => 'Offizielle Snowflake Docs.', 'en' => 'Official Snowflake docs.'],
                    ],
                    [
                        'label' => ['de' => 'Getting Started', 'en' => 'Getting started'],
                        'href' => 'https://docs.snowflake.com/en/user-guide-getting-started',
                        'description' => ['de' => 'Erste Schritte und Kernkonzepte.', 'en' => 'First steps and core concepts.'],
                    ],
                    [
                        'label' => ['de' => 'Dynamic Tables', 'en' => 'Dynamic tables'],
                        'href' => 'https://docs.snowflake.com/en/user-guide/dynamic-tables-about',
                        'description' => ['de' => 'Deklarative Transformationen.', 'en' => 'Declarative transformations.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Access Control', 'en' => 'Access control'],
                        'href' => 'https://docs.snowflake.com/en/user-guide/security-access-control-overview',
                        'description' => ['de' => 'RBAC: Roles und Grants.', 'en' => 'RBAC: roles and grants.'],
                    ],
                    [
                        'label' => ['de' => 'Column-level Security', 'en' => 'Column-level security'],
                        'href' => 'https://docs.snowflake.com/en/user-guide/security-column',
                        'description' => ['de' => 'Masking Policies.', 'en' => 'Masking policies.'],
                    ],
                    [
                        'label' => ['de' => 'Row Access Policies', 'en' => 'Row access policies'],
                        'href' => 'https://docs.snowflake.com/en/user-guide/security-row',
                        'description' => ['de' => 'Zeilenfilter.', 'en' => 'Row filters.'],
                    ],
                    [
                        'label' => ['de' => 'Object Tagging', 'en' => 'Object tagging'],
                        'href' => 'https://docs.snowflake.com/en/user-guide/object-tagging',
                        'description' => ['de' => 'Tags für Classification.', 'en' => 'Tags for classification.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Snowflake University', 'en' => 'Snowflake University'],
                        'href' => 'https://learn.snowflake.com/',
                        'description' => ['de' => 'Lernpfade und Hands-on Labs.', 'en' => 'Learning paths and hands-on labs.'],
                    ],
                    [
                        'label' => ['de' => 'Quickstarts', 'en' => 'Quickstarts'],
                        'href' => 'https://quickstarts.snowflake.com/',
                        'description' => ['de' => 'Geführte Workshops.', 'en' => 'Guided workshops.'],
                    ],
                    [
                        'label' => ['de' => 'Hands-on Essentials', 'en' => 'Hands-on Essentials'],
                        'href' => 'https://learn.snowflake.com/en/courses/uni_ess_101/',
                        'description' => ['de' => 'Einstiegspfad Essentials.', 'en' => 'Essentials onboarding path.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Snowflake Certifications', 'en' => 'Snowflake certifications'],
                        'href' => 'https://learn.snowflake.com/en/pages/certifications/',
                        'description' => ['de' => 'SnowPro Core und Spezialisierungen.', 'en' => 'SnowPro Core and specializations.'],
                    ],
                    [
                        'label' => ['de' => 'SnowPro Core', 'en' => 'SnowPro Core'],
                        'href' => 'https://learn.snowflake.com/en/courses/uni_cert_prep_core/',
                        'description' => ['de' => 'Core-Zertifizierungsvorbereitung.', 'en' => 'Core certification prep.'],
                    ],
            ],
        ],
        [
            'id' => 'databricks',
            'family' => 'platforms',
            'vendor' => 'databricks',
            'label' => ['de' => 'Databricks', 'en' => 'Databricks'],
            'purpose' => ['de' => 'Lakehouse-Plattform', 'en' => 'Lakehouse platform'],
            'models' => ['saas'],
            'brandColor' => '#FF3621',
            'logo' => 'images/databricks-badge.svg',
            'residency' => ['eu', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://www.databricks.com/trust/compliance',
                        'description' => ['de' => 'ISO 27001 und verwandte Controls.', 'en' => 'ISO 27001 and related controls.'],
                    ],
                    [
                        'id' => 'soc2',
                        'label' => ['de' => 'SOC 2', 'en' => 'SOC 2'],
                        'href' => 'https://www.databricks.com/trust/compliance',
                        'description' => ['de' => 'SOC-Reports.', 'en' => 'SOC reports.'],
                    ],
                    [
                        'id' => 'c5',
                        'label' => ['de' => 'BSI C5', 'en' => 'BSI C5'],
                        'href' => 'https://www.databricks.com/trust/compliance',
                        'description' => ['de' => 'BSI C5 Attestation.', 'en' => 'BSI C5 attestation.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Documentation', 'en' => 'Documentation'],
                        'href' => 'https://docs.databricks.com/',
                        'description' => ['de' => 'Workspace, SQL, Jobs und Lakehouse.', 'en' => 'Workspace, SQL, jobs, and lakehouse.'],
                    ],
                    [
                        'label' => ['de' => 'Lakeflow Pipelines', 'en' => 'Lakeflow Pipelines'],
                        'href' => 'https://docs.databricks.com/aws/en/ldp/',
                        'description' => ['de' => 'Deklarative Pipelines.', 'en' => 'Declarative pipelines.'],
                    ],
                    [
                        'label' => ['de' => 'Pipeline Expectations', 'en' => 'Pipeline expectations'],
                        'href' => 'https://docs.databricks.com/aws/en/ldp/expectations',
                        'description' => ['de' => 'DQ-Expectations in Pipelines.', 'en' => 'DQ expectations in pipelines.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Unity Catalog', 'en' => 'Unity Catalog'],
                        'href' => 'https://docs.databricks.com/aws/en/data-governance/unity-catalog/',
                        'description' => ['de' => 'Catalogs, Privileges und Lineage.', 'en' => 'Catalogs, privileges, and lineage.'],
                    ],
                    [
                        'label' => ['de' => 'Manage Privileges', 'en' => 'Manage privileges'],
                        'href' => 'https://docs.databricks.com/aws/en/data-governance/unity-catalog/manage-privileges/',
                        'description' => ['de' => 'Grants und Ownership.', 'en' => 'Grants and ownership.'],
                    ],
                    [
                        'label' => ['de' => 'Masks & Filters', 'en' => 'Masks & filters'],
                        'href' => 'https://docs.databricks.com/aws/en/data-governance/unity-catalog/filters-and-masks',
                        'description' => ['de' => 'Column Masking und Row Filters.', 'en' => 'Column masking and row filters.'],
                    ],
                    [
                        'label' => ['de' => 'Tags', 'en' => 'Tags'],
                        'href' => 'https://docs.databricks.com/aws/en/data-governance/unity-catalog/tags',
                        'description' => ['de' => 'Tags für Classification und Policies.', 'en' => 'Tags for classification and policies.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Databricks Academy', 'en' => 'Databricks Academy'],
                        'href' => 'https://www.databricks.com/learn/training/home',
                        'description' => ['de' => 'Offizielle Trainings und Lernpfade.', 'en' => 'Official training and learning paths.'],
                    ],
                    [
                        'label' => ['de' => 'Free Training', 'en' => 'Free training'],
                        'href' => 'https://www.databricks.com/learn/training/offers/free-training',
                        'description' => ['de' => 'Kostenlose Einstiegskurse.', 'en' => 'Free intro courses.'],
                    ],
                    [
                        'label' => ['de' => 'Lakehouse Fundamentals', 'en' => 'Lakehouse Fundamentals'],
                        'href' => 'https://www.databricks.com/learn/training/lakehouse-fundamentals',
                        'description' => ['de' => 'Grundlagen des Lakehouse.', 'en' => 'Lakehouse fundamentals.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Databricks Certifications', 'en' => 'Databricks certifications'],
                        'href' => 'https://www.databricks.com/learn/certification',
                        'description' => ['de' => 'Data Engineer, Analyst, Architect.', 'en' => 'Data Engineer, Analyst, Architect.'],
                    ],
                    [
                        'label' => ['de' => 'Data Engineer Associate', 'en' => 'Data Engineer Associate'],
                        'href' => 'https://www.databricks.com/learn/certification/data-engineer-associate',
                        'description' => ['de' => 'Associate-Zertifikat Data Engineering.', 'en' => 'Associate certificate for data engineering.'],
                    ],
            ],
        ],
        [
            'id' => 'bigquery',
            'family' => 'platforms',
            'vendor' => 'google',
            'label' => ['de' => 'BigQuery', 'en' => 'BigQuery'],
            'purpose' => ['de' => 'Serverless Data Warehouse', 'en' => 'Serverless data warehouse'],
            'models' => ['saas'],
            'brandColor' => '#4285F4',
            'logo' => 'images/bigquery-badge.svg',
            'residency' => ['eu', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://cloud.google.com/security/compliance',
                        'description' => ['de' => 'Google Cloud Compliance.', 'en' => 'Google Cloud compliance.'],
                    ],
                    [
                        'id' => 'soc2',
                        'label' => ['de' => 'SOC 2', 'en' => 'SOC 2'],
                        'href' => 'https://cloud.google.com/security/compliance',
                        'description' => ['de' => 'SOC-Reports über Google Cloud.', 'en' => 'SOC reports via Google Cloud.'],
                    ],
                    [
                        'id' => 'c5',
                        'label' => ['de' => 'BSI C5', 'en' => 'BSI C5'],
                        'href' => 'https://cloud.google.com/security/compliance/c5',
                        'description' => ['de' => 'C5 für Google Cloud.', 'en' => 'C5 for Google Cloud.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'BigQuery Documentation', 'en' => 'BigQuery documentation'],
                        'href' => 'https://cloud.google.com/bigquery/docs',
                        'description' => ['de' => 'Offizielle BigQuery Docs.', 'en' => 'Official BigQuery docs.'],
                    ],
                    [
                        'label' => ['de' => 'Quickstart', 'en' => 'Quickstart'],
                        'href' => 'https://cloud.google.com/bigquery/docs/quickstarts',
                        'description' => ['de' => 'Erste Abfragen und Datasets.', 'en' => 'First queries and datasets.'],
                    ],
                    [
                        'label' => ['de' => 'SQL Reference', 'en' => 'SQL reference'],
                        'href' => 'https://cloud.google.com/bigquery/docs/reference/standard-sql',
                        'description' => ['de' => 'Standard SQL Referenz.', 'en' => 'Standard SQL reference.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Column-level Security', 'en' => 'Column-level security'],
                        'href' => 'https://cloud.google.com/bigquery/docs/column-level-security-intro',
                        'description' => ['de' => 'Policy Tags und Spaltenzugriff.', 'en' => 'Policy tags and column access.'],
                    ],
                    [
                        'label' => ['de' => 'Row-level Security', 'en' => 'Row-level security'],
                        'href' => 'https://cloud.google.com/bigquery/docs/row-level-security-intro',
                        'description' => ['de' => 'Zeilenbasierte Zugriffsregeln.', 'en' => 'Row-based access rules.'],
                    ],
                    [
                        'label' => ['de' => 'Data Governance', 'en' => 'Data governance'],
                        'href' => 'https://cloud.google.com/bigquery/docs/data-governance',
                        'description' => ['de' => 'Governance-Funktionen in BigQuery.', 'en' => 'Governance features in BigQuery.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Google Cloud Skills Boost', 'en' => 'Google Cloud Skills Boost'],
                        'href' => 'https://www.cloudskillsboost.google/paths',
                        'description' => ['de' => 'Lernpfade zu BigQuery und Analytics.', 'en' => 'Learning paths for BigQuery and analytics.'],
                    ],
                    [
                        'label' => ['de' => 'BigQuery Path', 'en' => 'BigQuery path'],
                        'href' => 'https://www.cloudskillsboost.google/course_templates/55',
                        'description' => ['de' => 'BigQuery for Data Analysis.', 'en' => 'BigQuery for Data Analysis.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Professional Data Engineer', 'en' => 'Professional Data Engineer'],
                        'href' => 'https://cloud.google.com/learn/certification/data-engineer',
                        'description' => ['de' => 'Google Cloud Data Engineer Zertifikat.', 'en' => 'Google Cloud Data Engineer certificate.'],
                    ],
                    [
                        'label' => ['de' => 'Data Analyst Learning', 'en' => 'Data Analyst learning'],
                        'href' => 'https://cloud.google.com/learn/certification/looker-lookml-developer',
                        'description' => ['de' => 'Verwandte Analytics-Zertifizierungen.', 'en' => 'Related analytics certifications.'],
                    ],
            ],
        ],
        [
            'id' => 'fabric',
            'family' => 'platforms',
            'vendor' => 'microsoft',
            'bundles' => ['m365'],
            'label' => ['de' => 'Microsoft Fabric', 'en' => 'Microsoft Fabric'],
            'purpose' => ['de' => 'Analytics-Plattform', 'en' => 'Analytics platform'],
            'models' => ['saas'],
            'brandColor' => '#7F2BFF',
            'logo' => 'images/fabric-badge.svg',
            'residency' => ['eu', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-ISO-27001',
                        'description' => ['de' => 'Microsoft Compliance Offerings.', 'en' => 'Microsoft compliance offerings.'],
                    ],
                    [
                        'id' => 'soc2',
                        'label' => ['de' => 'SOC 2', 'en' => 'SOC 2'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-SOC',
                        'description' => ['de' => 'SOC-Reports.', 'en' => 'SOC reports.'],
                    ],
                    [
                        'id' => 'c5',
                        'label' => ['de' => 'BSI C5', 'en' => 'BSI C5'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-c5',
                        'description' => ['de' => 'BSI C5 für Azure/Microsoft Cloud.', 'en' => 'BSI C5 for Azure/Microsoft Cloud.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Fabric Documentation', 'en' => 'Fabric documentation'],
                        'href' => 'https://learn.microsoft.com/en-us/fabric/',
                        'description' => ['de' => 'Lakehouse, Warehouse und Pipelines.', 'en' => 'Lakehouse, warehouse, and pipelines.'],
                    ],
                    [
                        'label' => ['de' => 'Lakehouse Overview', 'en' => 'Lakehouse overview'],
                        'href' => 'https://learn.microsoft.com/en-us/fabric/data-engineering/lakehouse-overview',
                        'description' => ['de' => 'Lakehouse-Konzept in Fabric.', 'en' => 'Lakehouse concept in Fabric.'],
                    ],
                    [
                        'label' => ['de' => 'Data Warehouse', 'en' => 'Data warehouse'],
                        'href' => 'https://learn.microsoft.com/en-us/fabric/data-warehouse/',
                        'description' => ['de' => 'Warehouse-SQL und Reporting.', 'en' => 'Warehouse SQL and reporting.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Governance in Fabric', 'en' => 'Governance in Fabric'],
                        'href' => 'https://learn.microsoft.com/en-us/fabric/governance/',
                        'description' => ['de' => 'Governance und Purview-Integration.', 'en' => 'Governance and Purview integration.'],
                    ],
                    [
                        'label' => ['de' => 'Workspace Roles', 'en' => 'Workspace roles'],
                        'href' => 'https://learn.microsoft.com/en-us/fabric/fundamentals/roles-workspaces',
                        'description' => ['de' => 'Rollenmodell für Workspaces.', 'en' => 'Role model for workspaces.'],
                    ],
                    [
                        'label' => ['de' => 'Sensitivity Labels', 'en' => 'Sensitivity labels'],
                        'href' => 'https://learn.microsoft.com/en-us/fabric/governance/sensitivity-labels',
                        'description' => ['de' => 'Sensitivity Labels für Items.', 'en' => 'Sensitivity labels for items.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Fabric Learning Path', 'en' => 'Fabric learning path'],
                        'href' => 'https://learn.microsoft.com/en-us/training/paths/get-started-fabric/',
                        'description' => ['de' => 'Microsoft Learn Einstiegspfad.', 'en' => 'Microsoft Learn onboarding path.'],
                    ],
                    [
                        'label' => ['de' => 'Fabric Fundamentals', 'en' => 'Fabric fundamentals'],
                        'href' => 'https://learn.microsoft.com/en-us/training/modules/microsoft-fabric-get-started/',
                        'description' => ['de' => 'Grundlagenmodul Fabric.', 'en' => 'Fabric fundamentals module.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Fabric Analytics Engineer', 'en' => 'Fabric Analytics Engineer'],
                        'href' => 'https://learn.microsoft.com/en-us/credentials/certifications/fabric-analytics-engineer-associate/',
                        'description' => ['de' => 'DP-600 Zertifizierung.', 'en' => 'DP-600 certification.'],
                    ],
                    [
                        'label' => ['de' => 'Fabric Data Engineer', 'en' => 'Fabric Data Engineer'],
                        'href' => 'https://learn.microsoft.com/en-us/credentials/certifications/fabric-data-engineer-associate/',
                        'description' => ['de' => 'DP-700 Zertifizierung.', 'en' => 'DP-700 certification.'],
                    ],
            ],
        ],
        [
            'id' => 'sap',
            'family' => 'platforms',
            'vendor' => 'sap',
            'label' => ['de' => 'SAP', 'en' => 'SAP'],
            'purpose' => ['de' => 'Enterprise Data & Analytics', 'en' => 'Enterprise data & analytics'],
            'models' => ['saas', 'onprem'],
            'brandColor' => '#0FAAFF',
            'logo' => 'images/sap-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'SAP Help Portal', 'en' => 'SAP Help Portal'],
                        'href' => 'https://help.sap.com/',
                        'description' => ['de' => 'Offizielle Produktdokumentation.', 'en' => 'Official product documentation.'],
                    ],
                    [
                        'label' => ['de' => 'SAP Datasphere', 'en' => 'SAP Datasphere'],
                        'href' => 'https://help.sap.com/docs/SAP_DATASPHERE',
                        'description' => ['de' => 'Business Data Fabric / Datasphere.', 'en' => 'Business Data Fabric / Datasphere.'],
                    ],
                    [
                        'label' => ['de' => 'SAP Analytics Cloud', 'en' => 'SAP Analytics Cloud'],
                        'href' => 'https://help.sap.com/docs/SAP_ANALYTICS_CLOUD',
                        'description' => ['de' => 'SAC Reporting und Planning.', 'en' => 'SAC reporting and planning.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Datasphere Governance', 'en' => 'Datasphere governance'],
                        'href' => 'https://help.sap.com/docs/SAP_DATASPHERE/c8a54ee704e94e159265512762dec0d0/c8a54ee704e94e159265512762dec0d0.html',
                        'description' => ['de' => 'Spaces, Privileges und Data Products.', 'en' => 'Spaces, privileges, and data products.'],
                    ],
                    [
                        'label' => ['de' => 'Spaces & Privileges', 'en' => 'Spaces & privileges'],
                        'href' => 'https://help.sap.com/docs/SAP_DATASPHERE',
                        'description' => ['de' => 'Spaces, Privileges und Data Access in Datasphere.', 'en' => 'Spaces, privileges, and data access in Datasphere.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'SAP Learning', 'en' => 'SAP Learning'],
                        'href' => 'https://learning.sap.com/',
                        'description' => ['de' => 'Offizielle Lernplattform.', 'en' => 'Official learning platform.'],
                    ],
                    [
                        'label' => ['de' => 'Datasphere Learning', 'en' => 'Datasphere learning'],
                        'href' => 'https://learning.sap.com/products/business-technology-platform/data-analytics/datasphere',
                        'description' => ['de' => 'Lernressourcen zu Datasphere.', 'en' => 'Learning resources for Datasphere.'],
                    ],
                    [
                        'label' => ['de' => 'Analytics Cloud Learning', 'en' => 'Analytics Cloud learning'],
                        'href' => 'https://learning.sap.com/products/business-technology-platform/data-analytics/analytics-cloud',
                        'description' => ['de' => 'Lernressourcen zu SAC.', 'en' => 'Learning resources for SAC.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'SAP Certification Hub', 'en' => 'SAP Certification Hub'],
                        'href' => 'https://training.sap.com/certification/',
                        'description' => ['de' => 'Offizielle SAP-Zertifizierungen.', 'en' => 'Official SAP certifications.'],
                    ],
                    [
                        'label' => ['de' => 'BTP Data & Analytics', 'en' => 'BTP Data & Analytics'],
                        'href' => 'https://learning.sap.com/certifications',
                        'description' => ['de' => 'Zertifizierungspfade Data & Analytics.', 'en' => 'Data & analytics certification paths.'],
                    ],
            ],
        ],
        [
            'id' => 'pureview',
            'family' => 'platforms',
            'vendor' => 'microsoft',
            'label' => ['de' => 'Microsoft Purview', 'en' => 'Microsoft Purview'],
            'purpose' => ['de' => 'Data Governance & Catalog', 'en' => 'Data governance & catalog'],
            'models' => ['saas'],
            'brandColor' => '#0078D4',
            'logo' => 'images/pureview-badge.svg',
            'residency' => ['eu', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-ISO-27001',
                        'description' => ['de' => 'Über Microsoft Compliance.', 'en' => 'Via Microsoft compliance.'],
                    ],
                    [
                        'id' => 'c5',
                        'label' => ['de' => 'BSI C5', 'en' => 'BSI C5'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-c5',
                        'description' => ['de' => 'Relevant für Behörden.', 'en' => 'Relevant for public sector.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Purview Documentation', 'en' => 'Purview documentation'],
                        'href' => 'https://learn.microsoft.com/en-us/purview/',
                        'description' => ['de' => 'Data Map, Catalog und Governance.', 'en' => 'Data Map, Catalog, and governance.'],
                    ],
                    [
                        'label' => ['de' => 'Get Started', 'en' => 'Get started'],
                        'href' => 'https://learn.microsoft.com/en-us/purview/data-governance-get-started',
                        'description' => ['de' => 'Erste Schritte Unified Catalog.', 'en' => 'First steps Unified Catalog.'],
                    ],
                    [
                        'label' => ['de' => 'Glossary Terms', 'en' => 'Glossary terms'],
                        'href' => 'https://learn.microsoft.com/en-us/purview/unified-catalog-glossary-terms',
                        'description' => ['de' => 'Business Terms pflegen.', 'en' => 'Maintain business terms.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Governance Overview', 'en' => 'Governance overview'],
                        'href' => 'https://learn.microsoft.com/en-us/purview/data-governance-overview',
                        'description' => ['de' => 'Governance-Modell.', 'en' => 'Governance model.'],
                    ],
                    [
                        'label' => ['de' => 'Roles & Permissions', 'en' => 'Roles & permissions'],
                        'href' => 'https://learn.microsoft.com/en-us/purview/data-governance-roles-permissions',
                        'description' => ['de' => 'Rollen für Data Governance.', 'en' => 'Roles for data governance.'],
                    ],
                    [
                        'label' => ['de' => 'Classification', 'en' => 'Classification'],
                        'href' => 'https://learn.microsoft.com/en-us/purview/concept-classification',
                        'description' => ['de' => 'Klassifikation und SITs.', 'en' => 'Classification and SITs.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Purview Learning Path', 'en' => 'Purview learning path'],
                        'href' => 'https://learn.microsoft.com/en-us/training/paths/manage-data-governance-microsoft-purview/',
                        'description' => ['de' => 'Microsoft Learn Governance-Pfad.', 'en' => 'Microsoft Learn governance path.'],
                    ],
                    [
                        'label' => ['de' => 'Purview Fundamentals', 'en' => 'Purview fundamentals'],
                        'href' => 'https://learn.microsoft.com/en-us/training/modules/intro-to-microsoft-purview/',
                        'description' => ['de' => 'Einführungsmodul.', 'en' => 'Intro module.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Information Protection Admin', 'en' => 'Information Protection Admin'],
                        'href' => 'https://learn.microsoft.com/en-us/credentials/certifications/security-compliance-and-identity-fundamentals/',
                        'description' => ['de' => 'Verwandte Security/Compliance Credentials.', 'en' => 'Related security/compliance credentials.'],
                    ],
                    [
                        'label' => ['de' => 'SC-400 Overview', 'en' => 'SC-400 overview'],
                        'href' => 'https://learn.microsoft.com/en-us/credentials/certifications/information-protection-administrator/',
                        'description' => ['de' => 'Information Protection Administrator.', 'en' => 'Information Protection Administrator.'],
                    ],
            ],
        ],
        [
            'id' => 'dbt',
            'family' => 'transformation',
            'vendor' => 'dbt',
            'label' => ['de' => 'dbt', 'en' => 'dbt'],
            'purpose' => ['de' => 'Transformations- & Analytics-Engineering', 'en' => 'Transformation & analytics engineering'],
            'models' => ['saas', 'opensource', 'onprem'],
            'brandColor' => '#FF694B',
            'logo' => 'images/dbt-badge.svg',
            'residency' => ['eu', 'us'],
            'compliance' => [
                    [
                        'id' => 'soc2',
                        'label' => ['de' => 'SOC 2', 'en' => 'SOC 2'],
                        'href' => 'https://www.getdbt.com/security',
                        'description' => ['de' => 'dbt Cloud Security / SOC.', 'en' => 'dbt Cloud security / SOC.'],
                    ],
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://www.getdbt.com/security',
                        'description' => ['de' => 'Security-Programm.', 'en' => 'Security program.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'dbt Developer Hub', 'en' => 'dbt Developer Hub'],
                        'href' => 'https://docs.getdbt.com/',
                        'description' => ['de' => 'Einstieg in Docs, Guides und Referenz.', 'en' => 'Entry point for docs, guides, and reference.'],
                    ],
                    [
                        'label' => ['de' => 'Getting started', 'en' => 'Getting started'],
                        'href' => 'https://docs.getdbt.com/docs/get-started-dbt',
                        'description' => ['de' => 'Erste Schritte und Projektstruktur.', 'en' => 'First steps and project structure.'],
                    ],
                    [
                        'label' => ['de' => 'Data Tests', 'en' => 'Data tests'],
                        'href' => 'https://docs.getdbt.com/docs/build/data-tests',
                        'description' => ['de' => 'Tests im DAG.', 'en' => 'Tests in the DAG.'],
                    ],
                    [
                        'label' => ['de' => 'Snapshots', 'en' => 'Snapshots'],
                        'href' => 'https://docs.getdbt.com/docs/build/snapshots',
                        'description' => ['de' => 'Typ-2-Historie und Snapshots.', 'en' => 'Type-2 history and snapshots.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Meta Config', 'en' => 'Meta config'],
                        'href' => 'https://docs.getdbt.com/reference/resource-configs/meta',
                        'description' => ['de' => 'Meta für Ownership, PII und DQ.', 'en' => 'Meta for ownership, PII, and DQ.'],
                    ],
                    [
                        'label' => ['de' => 'Model Contracts', 'en' => 'Model contracts'],
                        'href' => 'https://docs.getdbt.com/docs/collaborate/govern/model-contracts',
                        'description' => ['de' => 'Schema-Verträge und Breaking Changes.', 'en' => 'Schema contracts and breaking changes.'],
                    ],
                    [
                        'label' => ['de' => 'Model Access', 'en' => 'Model access'],
                        'href' => 'https://docs.getdbt.com/docs/collaborate/govern/model-access',
                        'description' => ['de' => 'Private/Protected/Public Models.', 'en' => 'Private/protected/public models.'],
                    ],
                    [
                        'label' => ['de' => 'Exposures', 'en' => 'Exposures'],
                        'href' => 'https://docs.getdbt.com/docs/build/exposures',
                        'description' => ['de' => 'Downstream-Consumer dokumentieren.', 'en' => 'Document downstream consumers.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'dbt Learn', 'en' => 'dbt Learn'],
                        'href' => 'https://learn.getdbt.com/',
                        'description' => ['de' => 'Kostenlose und bezahlte Lernpfade.', 'en' => 'Free and paid learning paths.'],
                    ],
                    [
                        'label' => ['de' => 'dbt Fundamentals', 'en' => 'dbt Fundamentals'],
                        'href' => 'https://learn.getdbt.com/courses/dbt-fundamentals',
                        'description' => ['de' => 'Einstiegskurs für dbt Core/Cloud.', 'en' => 'Intro course for dbt Core/Cloud.'],
                    ],
                    [
                        'label' => ['de' => 'Best Practices Guide', 'en' => 'Best practices guide'],
                        'href' => 'https://docs.getdbt.com/best-practices',
                        'description' => ['de' => 'Empfohlene Projekt- und Modell-Patterns.', 'en' => 'Recommended project and model patterns.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'dbt Certification', 'en' => 'dbt Certification'],
                        'href' => 'https://www.getdbt.com/certifications',
                        'description' => ['de' => 'Offizielle dbt-Zertifizierungen.', 'en' => 'Official dbt certifications.'],
                    ],
                    [
                        'label' => ['de' => 'Analytics Engineering Certificate', 'en' => 'Analytics Engineering Certificate'],
                        'href' => 'https://learn.getdbt.com/courses/analytics-engineering',
                        'description' => ['de' => 'Zertifikatspfad Analytics Engineering.', 'en' => 'Analytics engineering certificate path.'],
                    ],
            ],
        ],
        [
            'id' => 'fivetran',
            'family' => 'transformation',
            'vendor' => 'fivetran',
            'label' => ['de' => 'Fivetran', 'en' => 'Fivetran'],
            'purpose' => ['de' => 'EL / Data Movement', 'en' => 'EL / data movement'],
            'models' => ['saas'],
            'brandColor' => '#0073FF',
            'logo' => 'images/fivetran-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Fivetran Documentation', 'en' => 'Fivetran documentation'],
                        'href' => 'https://fivetran.com/docs/getting-started',
                        'description' => ['de' => 'Getting Started und Connectors.', 'en' => 'Getting started and connectors.'],
                    ],
                    [
                        'label' => ['de' => 'Connector Directory', 'en' => 'Connector directory'],
                        'href' => 'https://fivetran.com/docs/connectors',
                        'description' => ['de' => 'Quellen und Destinationen.', 'en' => 'Sources and destinations.'],
                    ],
                    [
                        'label' => ['de' => 'Transformations', 'en' => 'Transformations'],
                        'href' => 'https://fivetran.com/docs/transformations',
                        'description' => ['de' => 'dbt Transformations mit Fivetran.', 'en' => 'dbt transformations with Fivetran.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Security Overview', 'en' => 'Security overview'],
                        'href' => 'https://fivetran.com/docs/security',
                        'description' => ['de' => 'Security und Compliance.', 'en' => 'Security and compliance.'],
                    ],
                    [
                        'label' => ['de' => 'Role-based Access', 'en' => 'Role-based access'],
                        'href' => 'https://fivetran.com/docs/using-fivetran/fivetran-dashboard/account-settings/role-based-access-control',
                        'description' => ['de' => 'RBAC im Dashboard.', 'en' => 'RBAC in the dashboard.'],
                    ],
                    [
                        'label' => ['de' => 'Private Networking', 'en' => 'Private networking'],
                        'href' => 'https://fivetran.com/docs/security/private-links',
                        'description' => ['de' => 'Private Links und Netzwerkpfade.', 'en' => 'Private links and network paths.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Fivetran Academy', 'en' => 'Fivetran Academy'],
                        'href' => 'https://fivetran.com/academy',
                        'description' => ['de' => 'Lernpfade und Kurse.', 'en' => 'Learning paths and courses.'],
                    ],
                    [
                        'label' => ['de' => 'Quickstart Guides', 'en' => 'Quickstart guides'],
                        'href' => 'https://fivetran.com/docs/getting-started/quickstart',
                        'description' => ['de' => 'Schnellstarts für Setup.', 'en' => 'Quickstarts for setup.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Fivetran Certification', 'en' => 'Fivetran certification'],
                        'href' => 'https://fivetran.com/academy',
                        'description' => ['de' => 'Zertifizierungsangebote in der Academy.', 'en' => 'Certification offerings in the Academy.'],
                    ],
            ],
        ],

        [
            'id' => 'talend',
            'family' => 'transformation',
            'vendor' => 'talend',
            'label' => ['de' => 'Talend', 'en' => 'Talend'],
            'purpose' => ['de' => 'Data Integration & Quality (Qlik)', 'en' => 'Data integration & quality (Qlik)'],
            'models' => ['saas', 'onprem'],
            'brandColor' => '#FF6D70',
            'logo' => 'images/talend-badge.svg',
            'residency' => ['eu', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://www.qlik.com/us/trust',
                        'description' => ['de' => 'Über Qlik Trust / Talend.', 'en' => 'Via Qlik Trust / Talend.'],
                    ],
                    [
                        'id' => 'soc2',
                        'label' => ['de' => 'SOC 2', 'en' => 'SOC 2'],
                        'href' => 'https://www.qlik.com/us/trust',
                        'description' => ['de' => 'SOC-Reports.', 'en' => 'SOC reports.'],
                    ],
                    [
                        'id' => 'gdpr',
                        'label' => ['de' => 'DSGVO / GDPR', 'en' => 'GDPR'],
                        'href' => 'https://www.qlik.com/us/trust',
                        'description' => ['de' => 'Datenschutz / Trust Center.', 'en' => 'Privacy / trust center.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Talend Help Center', 'en' => 'Talend Help Center'],
                        'href' => 'https://help.talend.com/',
                        'description' => ['de' => 'Offizielle Talend-Hilfe.', 'en' => 'Official Talend help.'],
                    ],
                    [
                        'label' => ['de' => 'Talend Cloud Docs', 'en' => 'Talend Cloud docs'],
                        'href' => 'https://help.talend.com/r/en-US/Cloud',
                        'description' => ['de' => 'Cloud-Produkte und Pipelines.', 'en' => 'Cloud products and pipelines.'],
                    ],
                    [
                        'label' => ['de' => 'Data Quality', 'en' => 'Data quality'],
                        'href' => 'https://help.talend.com/r/en-US/Cloud/data-inventory-user-guide-cloud',
                        'description' => ['de' => 'DQ und Inventory.', 'en' => 'DQ and inventory.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Qlik Trust Center', 'en' => 'Qlik Trust Center'],
                        'href' => 'https://www.qlik.com/us/trust',
                        'description' => ['de' => 'Security und Compliance (Qlik/Talend).', 'en' => 'Security and compliance (Qlik/Talend).'],
                    ],
                    [
                        'label' => ['de' => 'Talend Administration', 'en' => 'Talend administration'],
                        'href' => 'https://help.talend.com/r/en-US/Cloud/management-console-user-guide',
                        'description' => ['de' => 'Management Console / Admin.', 'en' => 'Management console / admin.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Qlik Learning (Talend)', 'en' => 'Qlik Learning (Talend)'],
                        'href' => 'https://learning.qlik.com/',
                        'description' => ['de' => 'Lernpfade inkl. Talend.', 'en' => 'Learning paths incl. Talend.'],
                    ],
                    [
                        'label' => ['de' => 'Talend Tutorials', 'en' => 'Talend tutorials'],
                        'href' => 'https://help.talend.com/r/en-US/Cloud/getting-started-guide',
                        'description' => ['de' => 'Getting Started Guides.', 'en' => 'Getting started guides.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Qlik Certification', 'en' => 'Qlik certification'],
                        'href' => 'https://www.qlik.com/us/services/training/qlik-certification-program',
                        'description' => ['de' => 'Zertifizierungen über Qlik.', 'en' => 'Certifications via Qlik.'],
                    ],
            ],
        ],
        [
            'id' => 'powerbi',
            'family' => 'bi',
            'vendor' => 'microsoft',
            'bundles' => ['m365'],
            'label' => ['de' => 'Power BI', 'en' => 'Power BI'],
            'purpose' => ['de' => 'BI & Reporting', 'en' => 'BI & reporting'],
            'models' => ['saas', 'onprem'],
            'brandColor' => '#F2C811',
            'logo' => 'images/powerbi-badge.svg',
            'residency' => ['eu', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-ISO-27001',
                        'description' => ['de' => 'Microsoft Compliance.', 'en' => 'Microsoft compliance.'],
                    ],
                    [
                        'id' => 'c5',
                        'label' => ['de' => 'BSI C5', 'en' => 'BSI C5'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-c5',
                        'description' => ['de' => 'BSI C5.', 'en' => 'BSI C5.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Power BI Documentation', 'en' => 'Power BI documentation'],
                        'href' => 'https://learn.microsoft.com/en-us/power-bi/',
                        'description' => ['de' => 'Desktop, Service und Admin.', 'en' => 'Desktop, Service, and admin.'],
                    ],
                    [
                        'label' => ['de' => 'Create Reports', 'en' => 'Create reports'],
                        'href' => 'https://learn.microsoft.com/en-us/power-bi/create-reports/',
                        'description' => ['de' => 'Reports und Visuals.', 'en' => 'Reports and visuals.'],
                    ],
                    [
                        'label' => ['de' => 'DAX Guide', 'en' => 'DAX guide'],
                        'href' => 'https://learn.microsoft.com/en-us/dax/',
                        'description' => ['de' => 'DAX-Referenz.', 'en' => 'DAX reference.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Admin Portal', 'en' => 'Admin portal'],
                        'href' => 'https://learn.microsoft.com/en-us/power-bi/admin/service-admin-portal',
                        'description' => ['de' => 'Tenant-Einstellungen.', 'en' => 'Tenant settings.'],
                    ],
                    [
                        'label' => ['de' => 'Row-level Security', 'en' => 'Row-level security'],
                        'href' => 'https://learn.microsoft.com/en-us/power-bi/enterprise/service-admin-rls',
                        'description' => ['de' => 'RLS in Datasets.', 'en' => 'RLS in datasets.'],
                    ],
                    [
                        'label' => ['de' => 'Sensitivity Labels', 'en' => 'Sensitivity labels'],
                        'href' => 'https://learn.microsoft.com/en-us/power-bi/enterprise/service-security-sensitivity-label-overview',
                        'description' => ['de' => 'Labels für Reports/Datasets.', 'en' => 'Labels for reports/datasets.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Power BI Learning Path', 'en' => 'Power BI learning path'],
                        'href' => 'https://learn.microsoft.com/en-us/training/powerplatform/power-bi',
                        'description' => ['de' => 'Microsoft Learn Pfade.', 'en' => 'Microsoft Learn paths.'],
                    ],
                    [
                        'label' => ['de' => 'Guided Learning', 'en' => 'Guided learning'],
                        'href' => 'https://learn.microsoft.com/en-us/training/powerplatform/power-bi?WT.mc_id=powerbi_landingpage',
                        'description' => ['de' => 'Geführtes Lernen.', 'en' => 'Guided learning.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Power BI Data Analyst', 'en' => 'Power BI Data Analyst'],
                        'href' => 'https://learn.microsoft.com/en-us/credentials/certifications/power-bi-data-analyst-associate/',
                        'description' => ['de' => 'PL-300 Zertifizierung.', 'en' => 'PL-300 certification.'],
                    ],
                    [
                        'label' => ['de' => 'Fabric Analytics Engineer', 'en' => 'Fabric Analytics Engineer'],
                        'href' => 'https://learn.microsoft.com/en-us/credentials/certifications/fabric-analytics-engineer-associate/',
                        'description' => ['de' => 'DP-600 (Fabric/Power BI).', 'en' => 'DP-600 (Fabric/Power BI).'],
                    ],
            ],
        ],
        [
            'id' => 'qlik',
            'family' => 'bi',
            'vendor' => 'qlik',
            'label' => ['de' => 'Qlik', 'en' => 'Qlik'],
            'purpose' => ['de' => 'BI & Associative Analytics', 'en' => 'BI & associative analytics'],
            'models' => ['saas', 'onprem'],
            'brandColor' => '#009845',
            'logo' => 'images/qlik-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Qlik Help', 'en' => 'Qlik Help'],
                        'href' => 'https://help.qlik.com/',
                        'description' => ['de' => 'Sense, Cloud und Scripting.', 'en' => 'Sense, Cloud, and scripting.'],
                    ],
                    [
                        'label' => ['de' => 'Load Script', 'en' => 'Load script'],
                        'href' => 'https://help.qlik.com/en-US/sense/Subsystems/Hub/Content/Sense_Hub/Scripting/script-code.htm',
                        'description' => ['de' => 'Datenmodell und Script.', 'en' => 'Data model and script.'],
                    ],
                    [
                        'label' => ['de' => 'Qlik Cloud Help', 'en' => 'Qlik Cloud Help'],
                        'href' => 'https://help.qlik.com/en-US/cloud-services/',
                        'description' => ['de' => 'Cloud Services Dokumentation.', 'en' => 'Cloud Services documentation.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Section Access', 'en' => 'Section Access'],
                        'href' => 'https://help.qlik.com/en-US/sense/Subsystems/Hub/Content/Sense_Hub/Scripting/Security/manage-security-with-section-access.htm',
                        'description' => ['de' => 'App-Autorisierung und Reduktion.', 'en' => 'App authorization and reduction.'],
                    ],
                    [
                        'label' => ['de' => 'Section Statement', 'en' => 'Section statement'],
                        'href' => 'https://help.qlik.com/en-US/sense/Subsystems/Hub/Content/Sense_Hub/Scripting/ScriptRegularStatements/Section.htm',
                        'description' => ['de' => 'Section Access vs Application.', 'en' => 'Section Access vs Application.'],
                    ],
                    [
                        'label' => ['de' => 'Space Permissions', 'en' => 'Space permissions'],
                        'href' => 'https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Admin/mc-space-permissions.htm',
                        'description' => ['de' => 'Rollen in Spaces.', 'en' => 'Roles in spaces.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Qlik Continuous Classroom', 'en' => 'Qlik Continuous Classroom'],
                        'href' => 'https://learning.qlik.com/',
                        'description' => ['de' => 'Offizielle Lernplattform.', 'en' => 'Official learning platform.'],
                    ],
                    [
                        'label' => ['de' => 'Qlik Help Tutorials', 'en' => 'Qlik Help tutorials'],
                        'href' => 'https://help.qlik.com/en-US/video/',
                        'description' => ['de' => 'Video-Tutorials.', 'en' => 'Video tutorials.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Qlik Certification', 'en' => 'Qlik certification'],
                        'href' => 'https://www.qlik.com/us/services/training/qlik-certification-program',
                        'description' => ['de' => 'Offizielle Zertifizierungsprogramme.', 'en' => 'Official certification programs.'],
                    ],
            ],
        ],
        [
            'id' => 'tableau',
            'family' => 'bi',
            'vendor' => 'tableau',
            'label' => ['de' => 'Tableau', 'en' => 'Tableau'],
            'purpose' => ['de' => 'BI & Visual Analytics', 'en' => 'BI & visual analytics'],
            'models' => ['saas', 'onprem'],
            'brandColor' => '#E97627',
            'logo' => 'images/tableau-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Tableau Help', 'en' => 'Tableau Help'],
                        'href' => 'https://help.tableau.com/',
                        'description' => ['de' => 'Desktop, Server und Cloud.', 'en' => 'Desktop, Server, and Cloud.'],
                    ],
                    [
                        'label' => ['de' => 'Get Started', 'en' => 'Get started'],
                        'href' => 'https://help.tableau.com/current/guides/get-started-tutorial/en-us/get-started-tutorial-home.htm',
                        'description' => ['de' => 'Erste Visualisierungen.', 'en' => 'First visualizations.'],
                    ],
                    [
                        'label' => ['de' => 'Calculated Fields', 'en' => 'Calculated fields'],
                        'href' => 'https://help.tableau.com/current/pro/desktop/en-us/calculations_calculatedfields.htm',
                        'description' => ['de' => 'Berechnungen und LOD.', 'en' => 'Calculations and LODs.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Permissions', 'en' => 'Permissions'],
                        'href' => 'https://help.tableau.com/current/server/en-us/permissions.htm',
                        'description' => ['de' => 'Berechtigungsregeln.', 'en' => 'Permission rules.'],
                    ],
                    [
                        'label' => ['de' => 'Row-level Security', 'en' => 'Row-level security'],
                        'href' => 'https://help.tableau.com/current/pro/desktop/en-us/rls_protectdata.htm',
                        'description' => ['de' => 'Datenzeilen schützen.', 'en' => 'Protect data rows.'],
                    ],
                    [
                        'label' => ['de' => 'Data Catalog', 'en' => 'Data Catalog'],
                        'href' => 'https://help.tableau.com/current/online/en-us/dm_catalog_overview.htm',
                        'description' => ['de' => 'Catalog und Lineage.', 'en' => 'Catalog and lineage.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Tableau Learning', 'en' => 'Tableau Learning'],
                        'href' => 'https://www.tableau.com/learn',
                        'description' => ['de' => 'Kurse und Lernpfade.', 'en' => 'Courses and learning paths.'],
                    ],
                    [
                        'label' => ['de' => 'Trailhead / eLearning', 'en' => 'Trailhead / eLearning'],
                        'href' => 'https://elearning.tableau.com/',
                        'description' => ['de' => 'eLearning und Trailhead.', 'en' => 'eLearning and Trailhead.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Tableau Credentials', 'en' => 'Tableau credentials'],
                        'href' => 'https://www.tableau.com/learn/certification',
                        'description' => ['de' => 'Desktop, Data Analyst, Architect.', 'en' => 'Desktop, Data Analyst, Architect.'],
                    ],
                    [
                        'label' => ['de' => 'Desktop Specialist', 'en' => 'Desktop Specialist'],
                        'href' => 'https://www.tableau.com/learn/certification/desktop-specialist',
                        'description' => ['de' => 'Einstiegszertifikat Desktop.', 'en' => 'Entry desktop certificate.'],
                    ],
            ],
        ],

        [
            'id' => 'metabase',
            'family' => 'bi',
            'vendor' => 'metabase',
            'label' => ['de' => 'Metabase', 'en' => 'Metabase'],
            'purpose' => ['de' => 'Open-Source BI & Dashboards', 'en' => 'Open-source BI & dashboards'],
            'models' => ['saas', 'opensource', 'onprem'],
            'brandColor' => '#509EE3',
            'logo' => 'images/metabase-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Metabase Docs', 'en' => 'Metabase docs'],
                        'href' => 'https://www.metabase.com/docs/latest/',
                        'description' => ['de' => 'Offizielle Dokumentation.', 'en' => 'Official documentation.'],
                    ],
                    [
                        'label' => ['de' => 'Installation', 'en' => 'Installation'],
                        'href' => 'https://www.metabase.com/docs/latest/installation-and-operation/installing-metabase',
                        'description' => ['de' => 'Self-hosting und Setup.', 'en' => 'Self-hosting and setup.'],
                    ],
                    [
                        'label' => ['de' => 'Questions & Dashboards', 'en' => 'Questions & dashboards'],
                        'href' => 'https://www.metabase.com/docs/latest/questions/start',
                        'description' => ['de' => 'Fragen, Charts und Dashboards.', 'en' => 'Questions, charts, and dashboards.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Permissions', 'en' => 'Permissions'],
                        'href' => 'https://www.metabase.com/docs/latest/permissions/introduction',
                        'description' => ['de' => 'Gruppen und Datenzugriff.', 'en' => 'Groups and data access.'],
                    ],
                    [
                        'label' => ['de' => 'Data Sandboxing / RLS', 'en' => 'Data sandboxing / RLS'],
                        'href' => 'https://www.metabase.com/docs/latest/permissions/data-sandboxes',
                        'description' => ['de' => 'Zeilenfilter und Sandboxes.', 'en' => 'Row filters and sandboxes.'],
                    ],
                    [
                        'label' => ['de' => 'Security Checklist', 'en' => 'Security checklist'],
                        'href' => 'https://www.metabase.com/docs/latest/installation-and-operation/security-checklist',
                        'description' => ['de' => 'Betrieb und Absicherung.', 'en' => 'Operations and hardening.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Learn Metabase', 'en' => 'Learn Metabase'],
                        'href' => 'https://www.metabase.com/learn/',
                        'description' => ['de' => 'Guides und Tutorials.', 'en' => 'Guides and tutorials.'],
                    ],
                    [
                        'label' => ['de' => 'Metabase Cloud', 'en' => 'Metabase Cloud'],
                        'href' => 'https://www.metabase.com/cloud/',
                        'description' => ['de' => 'Managed SaaS-Option.', 'en' => 'Managed SaaS option.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Metabase Academy', 'en' => 'Metabase Academy'],
                        'href' => 'https://www.metabase.com/learn/',
                        'description' => ['de' => 'Enablement über Learn-Inhalte.', 'en' => 'Enablement via Learn content.'],
                    ],
            ],
        ],
        [
            'id' => 'superset',
            'family' => 'bi',
            'vendor' => 'apache',
            'label' => ['de' => 'Apache Superset', 'en' => 'Apache Superset'],
            'purpose' => ['de' => 'Open-Source BI Platform', 'en' => 'Open-source BI platform'],
            'models' => ['opensource', 'onprem'],
            'brandColor' => '#20A7C9',
            'logo' => 'images/superset-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Superset Docs', 'en' => 'Superset docs'],
                        'href' => 'https://superset.apache.org/docs/intro',
                        'description' => ['de' => 'Offizielle Apache-Superset-Docs.', 'en' => 'Official Apache Superset docs.'],
                    ],
                    [
                        'label' => ['de' => 'Installing Superset', 'en' => 'Installing Superset'],
                        'href' => 'https://superset.apache.org/docs/installation/installing-superset-using-docker-compose',
                        'description' => ['de' => 'Docker Compose Setup.', 'en' => 'Docker Compose setup.'],
                    ],
                    [
                        'label' => ['de' => 'Creating Charts', 'en' => 'Creating charts'],
                        'href' => 'https://superset.apache.org/docs/using-superset/creating-charts-dashboards',
                        'description' => ['de' => 'Charts und Dashboards.', 'en' => 'Charts and dashboards.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Security Overview', 'en' => 'Security overview'],
                        'href' => 'https://superset.apache.org/docs/security/',
                        'description' => ['de' => 'Auth, Roles und Security.', 'en' => 'Auth, roles, and security.'],
                    ],
                    [
                        'label' => ['de' => 'Row Level Security', 'en' => 'Row level security'],
                        'href' => 'https://superset.apache.org/docs/security/#row-level-security',
                        'description' => ['de' => 'RLS-Regeln.', 'en' => 'RLS rules.'],
                    ],
                    [
                        'label' => ['de' => 'Roles & Permissions', 'en' => 'Roles & permissions'],
                        'href' => 'https://superset.apache.org/docs/security/#provided-roles',
                        'description' => ['de' => 'Standard-Rollen.', 'en' => 'Built-in roles.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Superset Tutorial', 'en' => 'Superset tutorial'],
                        'href' => 'https://superset.apache.org/docs/using-superset/creating-your-first-dashboard',
                        'description' => ['de' => 'Erstes Dashboard erstellen.', 'en' => 'Create your first dashboard.'],
                    ],
                    [
                        'label' => ['de' => 'Community Resources', 'en' => 'Community resources'],
                        'href' => 'https://superset.apache.org/community',
                        'description' => ['de' => 'Community und Support-Kanäle.', 'en' => 'Community and support channels.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Preset / Superset Learning', 'en' => 'Preset / Superset learning'],
                        'href' => 'https://preset.io/resources/',
                        'description' => ['de' => 'Lernmaterial rund um Superset.', 'en' => 'Learning material around Superset.'],
                    ],
            ],
        ],
        [
            'id' => 'lightdash',
            'family' => 'bi',
            'vendor' => 'lightdash',
            'label' => ['de' => 'Lightdash', 'en' => 'Lightdash'],
            'purpose' => ['de' => 'dbt-native Open-Source BI', 'en' => 'dbt-native open-source BI'],
            'models' => ['saas', 'opensource', 'onprem'],
            'brandColor' => '#0E9984',
            'logo' => 'images/lightdash-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Lightdash Docs', 'en' => 'Lightdash docs'],
                        'href' => 'https://docs.lightdash.com/',
                        'description' => ['de' => 'Offizielle Produktdokumentation.', 'en' => 'Official product documentation.'],
                    ],
                    [
                        'label' => ['de' => 'Self-host', 'en' => 'Self-host'],
                        'href' => 'https://docs.lightdash.com/self-host/self-host-lightdash',
                        'description' => ['de' => 'Self-hosting Guide.', 'en' => 'Self-hosting guide.'],
                    ],
                    [
                        'label' => ['de' => 'Getting Started', 'en' => 'Getting started'],
                        'href' => 'https://docs.lightdash.com/get-started/setup-lightdash/intro',
                        'description' => ['de' => 'Setup mit dbt-Projekt.', 'en' => 'Setup with a dbt project.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Spaces & Permissions', 'en' => 'Spaces & permissions'],
                        'href' => 'https://docs.lightdash.com/guides/spaces',
                        'description' => ['de' => 'Spaces und Zugriffskontrolle.', 'en' => 'Spaces and access control.'],
                    ],
                    [
                        'label' => ['de' => 'User Attributes / RLS', 'en' => 'User attributes / RLS'],
                        'href' => 'https://docs.lightdash.com/references/user-attributes',
                        'description' => ['de' => 'Attribute für Row-level Access.', 'en' => 'Attributes for row-level access.'],
                    ],
                    [
                        'label' => ['de' => 'Security', 'en' => 'Security'],
                        'href' => 'https://docs.lightdash.com/references/security',
                        'description' => ['de' => 'Security-Hinweise.', 'en' => 'Security notes.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Guides', 'en' => 'Guides'],
                        'href' => 'https://docs.lightdash.com/guides/how-to-create-metrics',
                        'description' => ['de' => 'Metrics, Charts und Dashboards.', 'en' => 'Metrics, charts, and dashboards.'],
                    ],
                    [
                        'label' => ['de' => 'dbt Integration', 'en' => 'dbt integration'],
                        'href' => 'https://docs.lightdash.com/get-started/setup-lightdash/connect-project',
                        'description' => ['de' => 'dbt-Projekt anbinden.', 'en' => 'Connect a dbt project.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Lightdash Docs Enablement', 'en' => 'Lightdash docs enablement'],
                        'href' => 'https://docs.lightdash.com/',
                        'description' => ['de' => 'Enablement über offizielle Docs.', 'en' => 'Enablement via official docs.'],
                    ],
            ],
        ],
        [
            'id' => 'atlan',
            'family' => 'catalogs',
            'vendor' => 'atlan',
            'label' => ['de' => 'Atlan', 'en' => 'Atlan'],
            'purpose' => ['de' => 'Active Metadata Catalog', 'en' => 'Active metadata catalog'],
            'models' => ['saas'],
            'brandColor' => '#4261F6',
            'logo' => 'images/atlan-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Atlan Documentation', 'en' => 'Atlan documentation'],
                        'href' => 'https://docs.atlan.com/',
                        'description' => ['de' => 'Discovery, Lineage und Glossary.', 'en' => 'Discovery, lineage, and glossary.'],
                    ],
                    [
                        'label' => ['de' => 'Discovery', 'en' => 'Discovery'],
                        'href' => 'https://docs.atlan.com/product/capabilities/discovery',
                        'description' => ['de' => 'Assets finden und verstehen.', 'en' => 'Find and understand assets.'],
                    ],
                    [
                        'label' => ['de' => 'Connectors', 'en' => 'Connectors'],
                        'href' => 'https://docs.atlan.com/product/connections',
                        'description' => ['de' => 'Quellen anbinden.', 'en' => 'Connect sources.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Stewardship', 'en' => 'Stewardship'],
                        'href' => 'https://docs.atlan.com/product/capabilities/governance/stewardship',
                        'description' => ['de' => 'Stewardship und Workflows.', 'en' => 'Stewardship and workflows.'],
                    ],
                    [
                        'label' => ['de' => 'Automate Governance', 'en' => 'Automate governance'],
                        'href' => 'https://docs.atlan.com/product/capabilities/governance/stewardship/how-tos/automate-data-governance',
                        'description' => ['de' => 'Governance-Workflows.', 'en' => 'Governance workflows.'],
                    ],
                    [
                        'label' => ['de' => 'Glossary', 'en' => 'Glossary'],
                        'href' => 'https://docs.atlan.com/product/capabilities/governance/glossary',
                        'description' => ['de' => 'Business Terms.', 'en' => 'Business terms.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Atlan University', 'en' => 'Atlan University'],
                        'href' => 'https://docs.atlan.com/',
                        'description' => ['de' => 'Onboarding und Strategy Guides.', 'en' => 'Onboarding and strategy guides.'],
                    ],
                    [
                        'label' => ['de' => 'Quick-start', 'en' => 'Quick-start'],
                        'href' => 'https://docs.atlan.com/product/capabilities/discovery',
                        'description' => ['de' => 'Schnelleinstieg Discovery.', 'en' => 'Discovery quick-start.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Atlan Docs / Enablement', 'en' => 'Atlan docs / enablement'],
                        'href' => 'https://docs.atlan.com/',
                        'description' => ['de' => 'Enablement über Produktdocs.', 'en' => 'Enablement via product docs.'],
                    ],
            ],
        ],
        [
            'id' => 'collibra',
            'family' => 'catalogs',
            'vendor' => 'collibra',
            'label' => ['de' => 'Collibra', 'en' => 'Collibra'],
            'purpose' => ['de' => 'Enterprise Data Catalog', 'en' => 'Enterprise data catalog'],
            'models' => ['saas', 'onprem'],
            'brandColor' => '#0B5FFF',
            'logo' => 'images/collibra-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Product Resource Center', 'en' => 'Product Resource Center'],
                        'href' => 'https://productresources.collibra.com/',
                        'description' => ['de' => 'Offizielle Produktdocs.', 'en' => 'Official product docs.'],
                    ],
                    [
                        'label' => ['de' => 'Data Catalog', 'en' => 'Data Catalog'],
                        'href' => 'https://productresources.collibra.com/docs/collibra/latest/Content/Catalog/to_catalog.htm',
                        'description' => ['de' => 'Catalog-Assets und Discovery.', 'en' => 'Catalog assets and discovery.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'About Stewardship', 'en' => 'About stewardship'],
                        'href' => 'https://productresources.collibra.com/docs/collibra/latest/Content/Stewardship/to_stewardship.htm',
                        'description' => ['de' => 'Stewardship-Rollen.', 'en' => 'Stewardship roles.'],
                    ],
                    [
                        'label' => ['de' => 'Catalog Workflows', 'en' => 'Catalog workflows'],
                        'href' => 'https://productresources.collibra.com/docs/collibra/latest/Content/Catalog/CatalogWorkflows/ref_catalog-workflows.htm',
                        'description' => ['de' => 'Governance-Workflows.', 'en' => 'Governance workflows.'],
                    ],
                    [
                        'label' => ['de' => 'Issue Roles', 'en' => 'Issue roles'],
                        'href' => 'https://productresources.collibra.com/docs/collibra/latest/Content/DataHelpdesk/co_issue-roles.htm',
                        'description' => ['de' => 'Data Helpdesk Rollen.', 'en' => 'Data Helpdesk roles.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Collibra University', 'en' => 'Collibra University'],
                        'href' => 'https://www.collibra.com/us/en/university',
                        'description' => ['de' => 'Trainings und Lernpfade.', 'en' => 'Training and learning paths.'],
                    ],
                    [
                        'label' => ['de' => 'Product Resources', 'en' => 'Product resources'],
                        'href' => 'https://productresources.collibra.com/',
                        'description' => ['de' => 'Guides und How-tos.', 'en' => 'Guides and how-tos.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Collibra Certifications', 'en' => 'Collibra certifications'],
                        'href' => 'https://www.collibra.com/us/en/university',
                        'description' => ['de' => 'Zertifizierungen über Collibra University.', 'en' => 'Certifications via Collibra University.'],
                    ],
            ],
        ],
        [
            'id' => 'alation',
            'family' => 'catalogs',
            'vendor' => 'alation',
            'label' => ['de' => 'Alation', 'en' => 'Alation'],
            'purpose' => ['de' => 'Data Catalog Platform', 'en' => 'Data catalog platform'],
            'models' => ['saas'],
            'brandColor' => '#00A3E0',
            'logo' => 'images/alation-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Alation Documentation', 'en' => 'Alation documentation'],
                        'href' => 'https://docs.alation.com/',
                        'description' => ['de' => 'Catalog, Search und Connectors.', 'en' => 'Catalog, search, and connectors.'],
                    ],
                    [
                        'label' => ['de' => 'Developer Portal', 'en' => 'Developer portal'],
                        'href' => 'https://developer.alation.com/dev/',
                        'description' => ['de' => 'APIs und Integrationen.', 'en' => 'APIs and integrations.'],
                    ],
                    [
                        'label' => ['de' => 'Open Connector Framework', 'en' => 'Open Connector Framework'],
                        'href' => 'https://docs.alation.com/en/latest/OpenConnectorFramework/index.html',
                        'description' => ['de' => 'Quellen anbinden.', 'en' => 'Connect sources.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Stewardship', 'en' => 'Stewardship'],
                        'href' => 'https://docs.alation.com/en/latest/steward/index.html',
                        'description' => ['de' => 'Policies und Stewardship.', 'en' => 'Policies and stewardship.'],
                    ],
                    [
                        'label' => ['de' => 'Cataloging Data', 'en' => 'Cataloging data'],
                        'href' => 'https://docs.alation.com/en/latest/steward/CatalogingData/index.html',
                        'description' => ['de' => 'Trust Flags und Curation.', 'en' => 'Trust flags and curation.'],
                    ],
                    [
                        'label' => ['de' => 'Lineage', 'en' => 'Lineage'],
                        'href' => 'https://docs.alation.com/en/latest/admins/HowToGuides/LineageOverview.html',
                        'description' => ['de' => 'Lineage und Impact.', 'en' => 'Lineage and impact.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Alation Docs / Guides', 'en' => 'Alation docs / guides'],
                        'href' => 'https://docs.alation.com/',
                        'description' => ['de' => 'Onboarding über Docs.', 'en' => 'Onboarding via docs.'],
                    ],
                    [
                        'label' => ['de' => 'Developer Guides', 'en' => 'Developer guides'],
                        'href' => 'https://developer.alation.com/dev/',
                        'description' => ['de' => 'API-Lernpfade.', 'en' => 'API learning paths.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Alation Enablement', 'en' => 'Alation enablement'],
                        'href' => 'https://docs.alation.com/',
                        'description' => ['de' => 'Enablement-Material in Docs.', 'en' => 'Enablement material in docs.'],
                    ],
            ],
        ],
        [
            'id' => 'datahub',
            'family' => 'catalogs',
            'vendor' => 'acryl',
            'label' => ['de' => 'DataHub', 'en' => 'DataHub'],
            'purpose' => ['de' => 'Open-Source Metadata Platform', 'en' => 'Open-source metadata platform'],
            'models' => ['opensource', 'onprem'],
            'brandColor' => '#FF6B35',
            'logo' => 'images/datahub-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'DataHub Docs', 'en' => 'DataHub docs'],
                        'href' => 'https://docs.datahubproject.io/',
                        'description' => ['de' => 'Setup, Features und APIs.', 'en' => 'Setup, features, and APIs.'],
                    ],
                    [
                        'label' => ['de' => 'Quickstart', 'en' => 'Quickstart'],
                        'href' => 'https://docs.datahubproject.io/docs/quickstart',
                        'description' => ['de' => 'Lokaler Start.', 'en' => 'Local start.'],
                    ],
                    [
                        'label' => ['de' => 'Metadata Ingestion', 'en' => 'Metadata ingestion'],
                        'href' => 'https://docs.datahubproject.io/docs/metadata-ingestion',
                        'description' => ['de' => 'Sources und Recipes.', 'en' => 'Sources and recipes.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Features Overview', 'en' => 'Features overview'],
                        'href' => 'https://docs.datahubproject.io/docs/features',
                        'description' => ['de' => 'Domains, Glossary, Tags.', 'en' => 'Domains, glossary, tags.'],
                    ],
                    [
                        'label' => ['de' => 'Lineage', 'en' => 'Lineage'],
                        'href' => 'https://docs.datahubproject.io/docs/features/feature-guides/lineage',
                        'description' => ['de' => 'End-to-End-Lineage.', 'en' => 'End-to-end lineage.'],
                    ],
                    [
                        'label' => ['de' => 'Access Policies', 'en' => 'Access policies'],
                        'href' => 'https://docs.datahubproject.io/docs/authorization/access-policies-guide',
                        'description' => ['de' => 'Zugriffspolicies.', 'en' => 'Access policies.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'DataHub Docs Guides', 'en' => 'DataHub docs guides'],
                        'href' => 'https://docs.datahubproject.io/docs/',
                        'description' => ['de' => 'Guides und Tutorials.', 'en' => 'Guides and tutorials.'],
                    ],
                    [
                        'label' => ['de' => 'Feature Guides', 'en' => 'Feature guides'],
                        'href' => 'https://docs.datahubproject.io/docs/features',
                        'description' => ['de' => 'Feature-orientierte Lernpfade.', 'en' => 'Feature-oriented learning paths.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Community Enablement', 'en' => 'Community enablement'],
                        'href' => 'https://docs.datahubproject.io/',
                        'description' => ['de' => 'Community-Docs als Enablement.', 'en' => 'Community docs as enablement.'],
                    ],
            ],
        ],
        [
            'id' => 'openmetadata',
            'family' => 'catalogs',
            'vendor' => 'openmetadata',
            'label' => ['de' => 'OpenMetadata', 'en' => 'OpenMetadata'],
            'purpose' => ['de' => 'Open-Source Data Catalog', 'en' => 'Open-source data catalog'],
            'models' => ['opensource', 'onprem'],
            'brandColor' => '#7147E8',
            'logo' => 'images/openmetadata-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'OpenMetadata Docs', 'en' => 'OpenMetadata docs'],
                        'href' => 'https://docs.open-metadata.org/',
                        'description' => ['de' => 'Catalog, Discovery und Collaboration.', 'en' => 'Catalog, discovery, and collaboration.'],
                    ],
                    [
                        'label' => ['de' => 'Deployment', 'en' => 'Deployment'],
                        'href' => 'https://docs.open-metadata.org/latest/deployment',
                        'description' => ['de' => 'Installation und Betrieb.', 'en' => 'Installation and operations.'],
                    ],
                    [
                        'label' => ['de' => 'Connectors', 'en' => 'Connectors'],
                        'href' => 'https://docs.open-metadata.org/latest/connectors',
                        'description' => ['de' => 'Warehouse- und BI-Connectors.', 'en' => 'Warehouse and BI connectors.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Data Governance', 'en' => 'Data governance'],
                        'href' => 'https://docs.open-metadata.org/latest/how-to-guides/data-governance',
                        'description' => ['de' => 'Domains und Policies.', 'en' => 'Domains and policies.'],
                    ],
                    [
                        'label' => ['de' => 'Data Lineage', 'en' => 'Data lineage'],
                        'href' => 'https://docs.open-metadata.org/latest/how-to-guides/data-lineage',
                        'description' => ['de' => 'Table-/Column-Lineage.', 'en' => 'Table/column lineage.'],
                    ],
                    [
                        'label' => ['de' => 'Data Quality', 'en' => 'Data quality'],
                        'href' => 'https://docs.open-metadata.org/latest/how-to-guides/data-quality-observability',
                        'description' => ['de' => 'Tests und Observability.', 'en' => 'Tests and observability.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'How-to Guides', 'en' => 'How-to guides'],
                        'href' => 'https://docs.open-metadata.org/latest/how-to-guides',
                        'description' => ['de' => 'Schrittische Guides.', 'en' => 'Practical guides.'],
                    ],
                    [
                        'label' => ['de' => 'Deployment Guides', 'en' => 'Deployment guides'],
                        'href' => 'https://docs.open-metadata.org/latest/deployment',
                        'description' => ['de' => 'Setup-Lernpfad.', 'en' => 'Setup learning path.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Docs Enablement', 'en' => 'Docs enablement'],
                        'href' => 'https://docs.open-metadata.org/',
                        'description' => ['de' => 'Enablement über Docs.', 'en' => 'Enablement via docs.'],
                    ],
            ],
        ],
        [
            'id' => 'openlineage',
            'family' => 'lineage',
            'vendor' => 'openlineage',
            'label' => ['de' => 'OpenLineage', 'en' => 'OpenLineage'],
            'purpose' => ['de' => 'Open Lineage Standard', 'en' => 'Open lineage standard'],
            'models' => ['opensource', 'onprem'],
            'brandColor' => '#3F51B5',
            'logo' => 'images/openlineage-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'OpenLineage Docs', 'en' => 'OpenLineage docs'],
                        'href' => 'https://openlineage.io/docs/',
                        'description' => ['de' => 'Spec und Integrationen.', 'en' => 'Spec and integrations.'],
                    ],
                    [
                        'label' => ['de' => 'Getting Started', 'en' => 'Getting started'],
                        'href' => 'https://openlineage.io/docs/get-started/',
                        'description' => ['de' => 'Events und Run-Lifecycle.', 'en' => 'Events and run lifecycle.'],
                    ],
                    [
                        'label' => ['de' => 'Integrations', 'en' => 'Integrations'],
                        'href' => 'https://openlineage.io/docs/integrations/',
                        'description' => ['de' => 'Airflow, Spark, dbt.', 'en' => 'Airflow, Spark, dbt.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Spec / Facets', 'en' => 'Spec / facets'],
                        'href' => 'https://openlineage.io/docs/spec/',
                        'description' => ['de' => 'Events und Datasets standardisieren.', 'en' => 'Standardize events and datasets.'],
                    ],
                    [
                        'label' => ['de' => 'Guides', 'en' => 'Guides'],
                        'href' => 'https://openlineage.io/docs/guides/',
                        'description' => ['de' => 'Consumer-Guides.', 'en' => 'Consumer guides.'],
                    ],
                    [
                        'label' => ['de' => 'OpenMetadata Connector', 'en' => 'OpenMetadata connector'],
                        'href' => 'https://docs.open-metadata.org/latest/connectors/pipeline/openlineage',
                        'description' => ['de' => 'Events in Catalog übernehmen.', 'en' => 'Ingest events into a catalog.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Get Started Path', 'en' => 'Get started path'],
                        'href' => 'https://openlineage.io/docs/get-started/',
                        'description' => ['de' => 'Einstiegspfad.', 'en' => 'Getting started path.'],
                    ],
                    [
                        'label' => ['de' => 'Integration Guides', 'en' => 'Integration guides'],
                        'href' => 'https://openlineage.io/docs/integrations/',
                        'description' => ['de' => 'Producer-Integrationen lernen.', 'en' => 'Learn producer integrations.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Community Spec', 'en' => 'Community spec'],
                        'href' => 'https://openlineage.io/docs/spec/',
                        'description' => ['de' => 'Spec als Referenz-Standard.', 'en' => 'Spec as reference standard.'],
                    ],
            ],
        ],
        [
            'id' => 'marquez',
            'family' => 'lineage',
            'vendor' => 'marquez',
            'label' => ['de' => 'Marquez', 'en' => 'Marquez'],
            'purpose' => ['de' => 'OpenLineage Collection Engine', 'en' => 'OpenLineage collection engine'],
            'models' => ['opensource', 'onprem'],
            'brandColor' => '#1B998B',
            'logo' => 'images/marquez-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Marquez Docs', 'en' => 'Marquez docs'],
                        'href' => 'https://marquezproject.ai/docs/',
                        'description' => ['de' => 'Collection Engine Docs.', 'en' => 'Collection engine docs.'],
                    ],
                    [
                        'label' => ['de' => 'Quickstart', 'en' => 'Quickstart'],
                        'href' => 'https://marquezproject.ai/docs/quickstart',
                        'description' => ['de' => 'Lokal starten.', 'en' => 'Start locally.'],
                    ],
                    [
                        'label' => ['de' => 'API Reference', 'en' => 'API reference'],
                        'href' => 'https://marquezproject.ai/openapi.html',
                        'description' => ['de' => 'REST-API.', 'en' => 'REST API.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'About / Model', 'en' => 'About / model'],
                        'href' => 'https://marquezproject.ai/docs/about/',
                        'description' => ['de' => 'Jobs und Datasets.', 'en' => 'Jobs and datasets.'],
                    ],
                    [
                        'label' => ['de' => 'Architecture', 'en' => 'Architecture'],
                        'href' => 'https://marquezproject.ai/docs/architecture/',
                        'description' => ['de' => 'API, UI und Store.', 'en' => 'API, UI, and store.'],
                    ],
                    [
                        'label' => ['de' => 'OpenLineage Integration', 'en' => 'OpenLineage integration'],
                        'href' => 'https://marquezproject.ai/docs/quickstart/',
                        'description' => ['de' => 'Events speichern und abfragen.', 'en' => 'Store and query events.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Quickstart Path', 'en' => 'Quickstart path'],
                        'href' => 'https://marquezproject.ai/docs/quickstart',
                        'description' => ['de' => 'Hands-on Einstieg.', 'en' => 'Hands-on start.'],
                    ],
                    [
                        'label' => ['de' => 'Architecture Guide', 'en' => 'Architecture guide'],
                        'href' => 'https://marquezproject.ai/docs/architecture/',
                        'description' => ['de' => 'Architektur verstehen.', 'en' => 'Understand the architecture.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Project Docs', 'en' => 'Project docs'],
                        'href' => 'https://marquezproject.ai/docs/',
                        'description' => ['de' => 'Docs als Enablement.', 'en' => 'Docs as enablement.'],
                    ],
            ],
        ],
        [
            'id' => 'chatgpt',
            'family' => 'ai',
            'vendor' => 'openai',
            'label' => ['de' => 'ChatGPT', 'en' => 'ChatGPT'],
            'purpose' => ['de' => 'Conversational AI / LLM', 'en' => 'Conversational AI / LLM'],
            'models' => ['saas'],
            'brandColor' => '#10A37F',
            'logo' => 'images/chatgpt-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'ChatGPT Help', 'en' => 'ChatGPT Help'],
                        'href' => 'https://help.openai.com/en/collections/3742471-chatgpt',
                        'description' => ['de' => 'Hilfe und How-tos für ChatGPT.', 'en' => 'Help and how-tos for ChatGPT.'],
                    ],
                    [
                        'label' => ['de' => 'OpenAI Platform Docs', 'en' => 'OpenAI Platform Docs'],
                        'href' => 'https://platform.openai.com/docs',
                        'description' => ['de' => 'API- und Modell-Dokumentation.', 'en' => 'API and model documentation.'],
                    ],
                    [
                        'label' => ['de' => 'Prompt Engineering Guide', 'en' => 'Prompt engineering guide'],
                        'href' => 'https://platform.openai.com/docs/guides/prompt-engineering',
                        'description' => ['de' => 'Prompts strukturieren und steuern.', 'en' => 'Structure and steer prompts.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Usage Policies', 'en' => 'Usage policies'],
                        'href' => 'https://openai.com/policies/usage-policies',
                        'description' => ['de' => 'Erlaubte Nutzung und Verbote.', 'en' => 'Allowed use and prohibitions.'],
                    ],
                    [
                        'label' => ['de' => 'Data Controls / Privacy', 'en' => 'Data controls / privacy'],
                        'href' => 'https://openai.com/enterprise-privacy',
                        'description' => ['de' => 'Enterprise Privacy und Datenkontrolle.', 'en' => 'Enterprise privacy and data controls.'],
                    ],
                    [
                        'label' => ['de' => 'Security Portal', 'en' => 'Security portal'],
                        'href' => 'https://trust.openai.com/',
                        'description' => ['de' => 'Security und Compliance.', 'en' => 'Security and compliance.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'OpenAI Cookbook', 'en' => 'OpenAI Cookbook'],
                        'href' => 'https://cookbook.openai.com/',
                        'description' => ['de' => 'Praktische Beispiele und Patterns.', 'en' => 'Practical examples and patterns.'],
                    ],
                    [
                        'label' => ['de' => 'ChatGPT for Work', 'en' => 'ChatGPT for work'],
                        'href' => 'https://help.openai.com/en/collections/8475203-chatgpt-enterprise-and-education',
                        'description' => ['de' => 'Enterprise-/Education-Guides.', 'en' => 'Enterprise/education guides.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'OpenAI Academy', 'en' => 'OpenAI Academy'],
                        'href' => 'https://academy.openai.com/',
                        'description' => ['de' => 'Lern- und Enablement-Angebote.', 'en' => 'Learning and enablement offerings.'],
                    ],
            ],
        ],
        [
            'id' => 'claude',
            'family' => 'ai',
            'vendor' => 'anthropic',
            'label' => ['de' => 'Claude', 'en' => 'Claude'],
            'purpose' => ['de' => 'Conversational AI / LLM', 'en' => 'Conversational AI / LLM'],
            'models' => ['saas'],
            'brandColor' => '#D97757',
            'logo' => 'images/claude-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Claude Docs', 'en' => 'Claude docs'],
                        'href' => 'https://docs.anthropic.com/',
                        'description' => ['de' => 'Offizielle Anthropic/Claude Docs.', 'en' => 'Official Anthropic/Claude docs.'],
                    ],
                    [
                        'label' => ['de' => 'API Overview', 'en' => 'API overview'],
                        'href' => 'https://docs.anthropic.com/en/api/getting-started',
                        'description' => ['de' => 'API-Einstieg und Authentifizierung.', 'en' => 'API getting started and auth.'],
                    ],
                    [
                        'label' => ['de' => 'Prompting Guide', 'en' => 'Prompting guide'],
                        'href' => 'https://docs.anthropic.com/en/docs/build-with-claude/prompt-engineering/overview',
                        'description' => ['de' => 'Prompt Engineering mit Claude.', 'en' => 'Prompt engineering with Claude.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Usage Policy', 'en' => 'Usage policy'],
                        'href' => 'https://www.anthropic.com/legal/aup',
                        'description' => ['de' => 'Acceptable Use Policy.', 'en' => 'Acceptable use policy.'],
                    ],
                    [
                        'label' => ['de' => 'Trust Center', 'en' => 'Trust center'],
                        'href' => 'https://trust.anthropic.com/',
                        'description' => ['de' => 'Security und Compliance.', 'en' => 'Security and compliance.'],
                    ],
                    [
                        'label' => ['de' => 'Constitutional AI', 'en' => 'Constitutional AI'],
                        'href' => 'https://www.anthropic.com/research/constitutional-ai-harmlessness-from-ai-feedback',
                        'description' => ['de' => 'Alignment- und Safety-Ansatz.', 'en' => 'Alignment and safety approach.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Claude Courses', 'en' => 'Claude courses'],
                        'href' => 'https://anthropic.skilljar.com/',
                        'description' => ['de' => 'Offizielle Claude-Lernangebote.', 'en' => 'Official Claude learning offerings.'],
                    ],
                    [
                        'label' => ['de' => 'Prompt Library', 'en' => 'Prompt library'],
                        'href' => 'https://docs.anthropic.com/en/resources/prompt-library/library',
                        'description' => ['de' => 'Beispiel-Prompts und Patterns.', 'en' => 'Example prompts and patterns.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Anthropic Academy', 'en' => 'Anthropic Academy'],
                        'href' => 'https://anthropic.skilljar.com/',
                        'description' => ['de' => 'Enablement über Anthropic Academy.', 'en' => 'Enablement via Anthropic Academy.'],
                    ],
            ],
        ],
        [
            'id' => 'cursor',
            'family' => 'ai',
            'vendor' => 'cursor',
            'label' => ['de' => 'Cursor', 'en' => 'Cursor'],
            'purpose' => ['de' => 'AI Code Editor', 'en' => 'AI code editor'],
            'models' => ['saas'],
            'brandColor' => '#8B5CF6',
            'logo' => 'images/cursor-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Cursor Docs', 'en' => 'Cursor docs'],
                        'href' => 'https://docs.cursor.com/',
                        'description' => ['de' => 'Offizielle Produktdokumentation.', 'en' => 'Official product documentation.'],
                    ],
                    [
                        'label' => ['de' => 'Get Started', 'en' => 'Get started'],
                        'href' => 'https://docs.cursor.com/get-started/welcome',
                        'description' => ['de' => 'Erste Schritte im Editor.', 'en' => 'First steps in the editor.'],
                    ],
                    [
                        'label' => ['de' => 'Agent / Chat', 'en' => 'Agent / chat'],
                        'href' => 'https://docs.cursor.com/chat/overview',
                        'description' => ['de' => 'Chat- und Agent-Workflows.', 'en' => 'Chat and agent workflows.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Privacy / Security', 'en' => 'Privacy / security'],
                        'href' => 'https://www.cursor.com/security',
                        'description' => ['de' => 'Security- und Privacy-Hinweise.', 'en' => 'Security and privacy notes.'],
                    ],
                    [
                        'label' => ['de' => 'Privacy Mode', 'en' => 'Privacy mode'],
                        'href' => 'https://docs.cursor.com/account/privacy',
                        'description' => ['de' => 'Datenkontrolle und Privacy Mode.', 'en' => 'Data controls and privacy mode.'],
                    ],
                    [
                        'label' => ['de' => 'Terms of Service', 'en' => 'Terms of service'],
                        'href' => 'https://www.cursor.com/terms-of-service',
                        'description' => ['de' => 'Nutzungsbedingungen.', 'en' => 'Terms of service.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Cursor Learn', 'en' => 'Cursor Learn'],
                        'href' => 'https://docs.cursor.com/',
                        'description' => ['de' => 'Guides und Feature-Docs.', 'en' => 'Guides and feature docs.'],
                    ],
                    [
                        'label' => ['de' => 'Rules / Memories', 'en' => 'Rules / memories'],
                        'href' => 'https://docs.cursor.com/context/rules',
                        'description' => ['de' => 'Projektregeln und Kontext steuern.', 'en' => 'Steer project rules and context.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Docs Enablement', 'en' => 'Docs enablement'],
                        'href' => 'https://docs.cursor.com/',
                        'description' => ['de' => 'Enablement über offizielle Docs.', 'en' => 'Enablement via official docs.'],
                    ],
            ],
        ],
        [
            'id' => 'codex',
            'family' => 'ai',
            'vendor' => 'openai',
            'label' => ['de' => 'OpenAI Codex', 'en' => 'OpenAI Codex'],
            'purpose' => ['de' => 'AI Coding Agent', 'en' => 'AI coding agent'],
            'models' => ['saas'],
            'brandColor' => '#10A37F',
            'logo' => 'images/codex-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Codex Docs', 'en' => 'Codex docs'],
                        'href' => 'https://developers.openai.com/codex',
                        'description' => ['de' => 'OpenAI Codex Entwicklerdocs.', 'en' => 'OpenAI Codex developer docs.'],
                    ],
                    [
                        'label' => ['de' => 'Codex CLI', 'en' => 'Codex CLI'],
                        'href' => 'https://developers.openai.com/codex/cli',
                        'description' => ['de' => 'CLI-Nutzung und Workflows.', 'en' => 'CLI usage and workflows.'],
                    ],
                    [
                        'label' => ['de' => 'Platform Overview', 'en' => 'Platform overview'],
                        'href' => 'https://platform.openai.com/docs',
                        'description' => ['de' => 'OpenAI Platform Kontext.', 'en' => 'OpenAI Platform context.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Usage Policies', 'en' => 'Usage policies'],
                        'href' => 'https://openai.com/policies/usage-policies',
                        'description' => ['de' => 'Nutzungsrichtlinien.', 'en' => 'Usage policies.'],
                    ],
                    [
                        'label' => ['de' => 'Enterprise Privacy', 'en' => 'Enterprise privacy'],
                        'href' => 'https://openai.com/enterprise-privacy',
                        'description' => ['de' => 'Daten- und Privacy-Kontrollen.', 'en' => 'Data and privacy controls.'],
                    ],
                    [
                        'label' => ['de' => 'Trust Portal', 'en' => 'Trust portal'],
                        'href' => 'https://trust.openai.com/',
                        'description' => ['de' => 'Security und Compliance.', 'en' => 'Security and compliance.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Codex Guides', 'en' => 'Codex guides'],
                        'href' => 'https://developers.openai.com/codex',
                        'description' => ['de' => 'Guides und Best Practices.', 'en' => 'Guides and best practices.'],
                    ],
                    [
                        'label' => ['de' => 'OpenAI Cookbook', 'en' => 'OpenAI Cookbook'],
                        'href' => 'https://cookbook.openai.com/',
                        'description' => ['de' => 'Praktische Coding-/API-Beispiele.', 'en' => 'Practical coding/API examples.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'OpenAI Academy', 'en' => 'OpenAI Academy'],
                        'href' => 'https://academy.openai.com/',
                        'description' => ['de' => 'Lern- und Enablement-Angebote.', 'en' => 'Learning and enablement offerings.'],
                    ],
            ],
        ],
        [
            'id' => 'github-copilot',
            'family' => 'ai',
            'vendor' => 'github',
            'label' => ['de' => 'GitHub Copilot', 'en' => 'GitHub Copilot'],
            'purpose' => ['de' => 'AI Pair Programmer', 'en' => 'AI pair programmer'],
            'models' => ['saas'],
            'brandColor' => '#A371F7',
            'logo' => 'images/github-copilot-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Copilot Docs', 'en' => 'Copilot docs'],
                        'href' => 'https://docs.github.com/en/copilot',
                        'description' => ['de' => 'Offizielle GitHub-Copilot-Dokumentation.', 'en' => 'Official GitHub Copilot documentation.'],
                    ],
                    [
                        'label' => ['de' => 'Getting Started', 'en' => 'Getting started'],
                        'href' => 'https://docs.github.com/en/copilot/get-started/quickstart',
                        'description' => ['de' => 'Schnellstart und Setup.', 'en' => 'Quickstart and setup.'],
                    ],
                    [
                        'label' => ['de' => 'Copilot Chat', 'en' => 'Copilot Chat'],
                        'href' => 'https://docs.github.com/en/copilot/github-copilot-chat',
                        'description' => ['de' => 'Chat in IDE und GitHub.com.', 'en' => 'Chat in IDE and on GitHub.com.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Copilot Trust Center', 'en' => 'Copilot trust center'],
                        'href' => 'https://resources.github.com/copilot-trust-center/',
                        'description' => ['de' => 'Privacy, Security und Compliance.', 'en' => 'Privacy, security, and compliance.'],
                    ],
                    [
                        'label' => ['de' => 'Data Exclusion / Policies', 'en' => 'Data exclusion / policies'],
                        'href' => 'https://docs.github.com/en/copilot/managing-copilot/managing-github-copilot-in-your-organization/managing-policies-for-copilot-in-your-organization',
                        'description' => ['de' => 'Org-Policies und Steuerung.', 'en' => 'Org policies and controls.'],
                    ],
                    [
                        'label' => ['de' => 'Content Exclusion', 'en' => 'Content exclusion'],
                        'href' => 'https://docs.github.com/en/copilot/managing-copilot/managing-github-copilot-in-your-organization/configuring-content-exclusions-for-github-copilot',
                        'description' => ['de' => 'Repos von Indexierung ausschließen.', 'en' => 'Exclude content from indexing.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Copilot Skills', 'en' => 'Copilot skills'],
                        'href' => 'https://skills.github.com/',
                        'description' => ['de' => 'Interaktive GitHub-Skills.', 'en' => 'Interactive GitHub Skills.'],
                    ],
                    [
                        'label' => ['de' => 'Best Practices', 'en' => 'Best practices'],
                        'href' => 'https://docs.github.com/en/copilot/using-github-copilot/best-practices-for-using-github-copilot',
                        'description' => ['de' => 'Effektive Nutzung von Copilot.', 'en' => 'Use Copilot effectively.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'GitHub Certifications', 'en' => 'GitHub Certifications'],
                        'href' => 'https://resources.github.com/learn/certifications/',
                        'description' => ['de' => 'Offizielle GitHub-Zertifizierungen.', 'en' => 'Official GitHub certifications.'],
                    ],
            ],
        ],
        [
            'id' => 'github',
            'family' => 'platforms',
            'vendor' => 'github',
            'label' => ['de' => 'GitHub', 'en' => 'GitHub'],
            'purpose' => ['de' => 'Code Hosting & Dev Platform', 'en' => 'Code hosting & dev platform'],
            'models' => ['saas', 'onprem'],
            'brandColor' => '#0969DA',
            'logo' => 'images/github-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'GitHub Docs', 'en' => 'GitHub docs'],
                        'href' => 'https://docs.github.com/en',
                        'description' => ['de' => 'Offizielle GitHub-Dokumentation.', 'en' => 'Official GitHub documentation.'],
                    ],
                    [
                        'label' => ['de' => 'Actions', 'en' => 'Actions'],
                        'href' => 'https://docs.github.com/en/actions',
                        'description' => ['de' => 'CI/CD mit GitHub Actions.', 'en' => 'CI/CD with GitHub Actions.'],
                    ],
                    [
                        'label' => ['de' => 'REST / GraphQL API', 'en' => 'REST / GraphQL API'],
                        'href' => 'https://docs.github.com/en/rest',
                        'description' => ['de' => 'API-Referenz.', 'en' => 'API reference.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Organization Security', 'en' => 'Organization security'],
                        'href' => 'https://docs.github.com/en/organizations/keeping-your-organization-secure',
                        'description' => ['de' => 'Org-Sicherheit und Zugriffe.', 'en' => 'Org security and access.'],
                    ],
                    [
                        'label' => ['de' => 'Code Security', 'en' => 'Code security'],
                        'href' => 'https://docs.github.com/en/code-security',
                        'description' => ['de' => 'Secret Scanning, Dependabot, CodeQL.', 'en' => 'Secret scanning, Dependabot, CodeQL.'],
                    ],
                    [
                        'label' => ['de' => 'Trust Center', 'en' => 'Trust center'],
                        'href' => 'https://github.com/security',
                        'description' => ['de' => 'Security und Compliance.', 'en' => 'Security and compliance.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'GitHub Skills', 'en' => 'GitHub Skills'],
                        'href' => 'https://skills.github.com/',
                        'description' => ['de' => 'Interaktive Lernpfade.', 'en' => 'Interactive learning paths.'],
                    ],
                    [
                        'label' => ['de' => 'GitHub Learning Pathways', 'en' => 'GitHub learning pathways'],
                        'href' => 'https://resources.github.com/learn/pathways/',
                        'description' => ['de' => 'Strukturierte Lernpfade.', 'en' => 'Structured learning pathways.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'GitHub Certifications', 'en' => 'GitHub Certifications'],
                        'href' => 'https://resources.github.com/learn/certifications/',
                        'description' => ['de' => 'Foundations, Actions, Admin, Security.', 'en' => 'Foundations, Actions, Admin, Security.'],
                    ],
            ],
        ],
        [
            'id' => 'microsoft-copilot',
            'family' => 'ai',
            'vendor' => 'microsoft',
            'bundles' => ['m365'],
            'label' => ['de' => 'Microsoft Copilot', 'en' => 'Microsoft Copilot'],
            'purpose' => ['de' => 'Microsoft 365 / Azure AI Assistant', 'en' => 'Microsoft 365 / Azure AI assistant'],
            'models' => ['saas'],
            'brandColor' => '#7B83EB',
            'logo' => 'images/microsoft-copilot-badge.svg',
            'help' => [
                    [
                        'label' => ['de' => 'Copilot Documentation', 'en' => 'Copilot documentation'],
                        'href' => 'https://learn.microsoft.com/en-us/copilot/',
                        'description' => ['de' => 'Überblick über Microsoft Copilot.', 'en' => 'Overview of Microsoft Copilot.'],
                    ],
                    [
                        'label' => ['de' => 'Microsoft 365 Copilot', 'en' => 'Microsoft 365 Copilot'],
                        'href' => 'https://learn.microsoft.com/en-us/copilot/microsoft-365/',
                        'description' => ['de' => 'Copilot in Microsoft 365.', 'en' => 'Copilot in Microsoft 365.'],
                    ],
                    [
                        'label' => ['de' => 'Copilot Studio', 'en' => 'Copilot Studio'],
                        'href' => 'https://learn.microsoft.com/en-us/microsoft-copilot-studio/',
                        'description' => ['de' => 'Eigene Agents bauen.', 'en' => 'Build custom agents.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Data Privacy & Security', 'en' => 'Data privacy & security'],
                        'href' => 'https://learn.microsoft.com/en-us/copilot/microsoft-365/microsoft-365-copilot-privacy',
                        'description' => ['de' => 'Privacy und Datenfluss.', 'en' => 'Privacy and data flow.'],
                    ],
                    [
                        'label' => ['de' => 'Admin Controls', 'en' => 'Admin controls'],
                        'href' => 'https://learn.microsoft.com/en-us/microsoft-365/admin/manage/manage-copilot',
                        'description' => ['de' => 'Admin-Steuerung für Copilot.', 'en' => 'Admin controls for Copilot.'],
                    ],
                    [
                        'label' => ['de' => 'Trust Center', 'en' => 'Trust center'],
                        'href' => 'https://www.microsoft.com/en-us/trust-center',
                        'description' => ['de' => 'Microsoft Trust Center.', 'en' => 'Microsoft Trust Center.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Copilot Learning Hub', 'en' => 'Copilot learning hub'],
                        'href' => 'https://learn.microsoft.com/en-us/training/copilot/',
                        'description' => ['de' => 'Microsoft Learn für Copilot.', 'en' => 'Microsoft Learn for Copilot.'],
                    ],
                    [
                        'label' => ['de' => 'Adoption Guide', 'en' => 'Adoption guide'],
                        'href' => 'https://adoption.microsoft.com/en-us/copilot/',
                        'description' => ['de' => 'Adoption und Enablement.', 'en' => 'Adoption and enablement.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'MS-4004 / Copilot Credentials', 'en' => 'MS-4004 / Copilot credentials'],
                        'href' => 'https://learn.microsoft.com/en-us/credentials/certifications/exams/ms-4004/',
                        'description' => ['de' => 'Copilot-bezogene Lernzertifikate.', 'en' => 'Copilot-related learning credentials.'],
                    ],
            ],
        ],

        [
            'id' => 'miro',
            'family' => 'planning',
            'vendor' => 'miro',
            'label' => ['de' => 'Miro', 'en' => 'Miro'],
            'purpose' => ['de' => 'Collaborative Whiteboard & Planning', 'en' => 'Collaborative whiteboard & planning'],
            'models' => ['saas'],
            'brandColor' => '#FFD02F',
            'logo' => 'images/miro-badge.svg',
            'residency' => ['eu', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://miro.com/trust/',
                        'description' => ['de' => 'Trust Center / ISO.', 'en' => 'Trust center / ISO.'],
                    ],
                    [
                        'id' => 'soc2',
                        'label' => ['de' => 'SOC 2', 'en' => 'SOC 2'],
                        'href' => 'https://miro.com/trust/',
                        'description' => ['de' => 'SOC 2 Type II.', 'en' => 'SOC 2 Type II.'],
                    ],
                    [
                        'id' => 'gdpr',
                        'label' => ['de' => 'DSGVO / GDPR', 'en' => 'GDPR'],
                        'href' => 'https://miro.com/legal/privacy-policy/',
                        'description' => ['de' => 'Privacy und EU-Optionen.', 'en' => 'Privacy and EU options.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Miro Help Center', 'en' => 'Miro Help Center'],
                        'href' => 'https://help.miro.com/',
                        'description' => ['de' => 'Hilfe und How-tos.', 'en' => 'Help and how-tos.'],
                    ],
                    [
                        'label' => ['de' => 'Miro Developer Docs', 'en' => 'Miro developer docs'],
                        'href' => 'https://developers.miro.com/docs',
                        'description' => ['de' => 'API und Apps.', 'en' => 'API and apps.'],
                    ],
                    [
                        'label' => ['de' => 'Templates', 'en' => 'Templates'],
                        'href' => 'https://miro.com/templates/',
                        'description' => ['de' => 'Planungs- und Workshop-Templates.', 'en' => 'Planning and workshop templates.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Miro Trust Center', 'en' => 'Miro Trust Center'],
                        'href' => 'https://miro.com/trust/',
                        'description' => ['de' => 'Security und Compliance.', 'en' => 'Security and compliance.'],
                    ],
                    [
                        'label' => ['de' => 'Admin / Enterprise Controls', 'en' => 'Admin / enterprise controls'],
                        'href' => 'https://help.miro.com/hc/en-us/categories/360001281760-Admin',
                        'description' => ['de' => 'Admin- und Enterprise-Steuerung.', 'en' => 'Admin and enterprise controls.'],
                    ],
                    [
                        'label' => ['de' => 'Data Residency', 'en' => 'Data residency'],
                        'href' => 'https://help.miro.com/hc/en-us/articles/360017572819-Data-residency',
                        'description' => ['de' => 'Datenresidenz-Optionen.', 'en' => 'Data residency options.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Miro Academy', 'en' => 'Miro Academy'],
                        'href' => 'https://academy.miro.com/',
                        'description' => ['de' => 'Kurse und Enablement.', 'en' => 'Courses and enablement.'],
                    ],
                    [
                        'label' => ['de' => 'Agile / Planning Guides', 'en' => 'Agile / planning guides'],
                        'href' => 'https://miro.com/agile/',
                        'description' => ['de' => 'Agile Planning mit Miro.', 'en' => 'Agile planning with Miro.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Miro Expert', 'en' => 'Miro Expert'],
                        'href' => 'https://academy.miro.com/',
                        'description' => ['de' => 'Academy / Expert-Programme.', 'en' => 'Academy / expert programs.'],
                    ],
            ],
        ],
        [
            'id' => 'microsoft-whiteboard',
            'family' => 'planning',
            'vendor' => 'microsoft',
            'bundles' => ['m365'],
            'label' => ['de' => 'Microsoft Whiteboard', 'en' => 'Microsoft Whiteboard'],
            'purpose' => ['de' => 'Whiteboard in Microsoft 365', 'en' => 'Whiteboard in Microsoft 365'],
            'models' => ['saas'],
            'brandColor' => '#5B5FC7',
            'logo' => 'images/microsoft-whiteboard-badge.svg',
            'residency' => ['eu', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-ISO-27001',
                        'description' => ['de' => 'Über Microsoft Compliance.', 'en' => 'Via Microsoft compliance.'],
                    ],
                    [
                        'id' => 'gdpr',
                        'label' => ['de' => 'DSGVO / GDPR', 'en' => 'GDPR'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/gdpr',
                        'description' => ['de' => 'DSGVO-Guidance.', 'en' => 'GDPR guidance.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Whiteboard Help', 'en' => 'Whiteboard help'],
                        'href' => 'https://support.microsoft.com/en-us/whiteboard',
                        'description' => ['de' => 'Hilfe zu Microsoft Whiteboard.', 'en' => 'Help for Microsoft Whiteboard.'],
                    ],
                    [
                        'label' => ['de' => 'Whiteboard Docs', 'en' => 'Whiteboard docs'],
                        'href' => 'https://learn.microsoft.com/en-us/microsoft-365/whiteboard/',
                        'description' => ['de' => 'Admin- und Feature-Docs.', 'en' => 'Admin and feature docs.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Admin Controls', 'en' => 'Admin controls'],
                        'href' => 'https://learn.microsoft.com/en-us/microsoft-365/whiteboard/manage-whiteboard-access-organizations',
                        'description' => ['de' => 'Zugriff und Org-Steuerung.', 'en' => 'Access and org controls.'],
                    ],
                    [
                        'label' => ['de' => 'Data Governance', 'en' => 'Data governance'],
                        'href' => 'https://learn.microsoft.com/en-us/microsoft-365/whiteboard/manage-data-organizations',
                        'description' => ['de' => 'Datenverwaltung.', 'en' => 'Data management.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Whiteboard Adoption', 'en' => 'Whiteboard adoption'],
                        'href' => 'https://adoption.microsoft.com/en-us/whiteboard/',
                        'description' => ['de' => 'Adoption Guide.', 'en' => 'Adoption guide.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Microsoft 365 Training', 'en' => 'Microsoft 365 training'],
                        'href' => 'https://learn.microsoft.com/en-us/training/microsoft-365/',
                        'description' => ['de' => 'M365 Learn Pfade.', 'en' => 'M365 Learn paths.'],
                    ],
            ],
        ],
        [
            'id' => 'microsoft-planner',
            'family' => 'planning',
            'vendor' => 'microsoft',
            'bundles' => ['m365'],
            'label' => ['de' => 'Microsoft Planner', 'en' => 'Microsoft Planner'],
            'purpose' => ['de' => 'Task & Sprint Planning in M365', 'en' => 'Task & sprint planning in M365'],
            'models' => ['saas'],
            'brandColor' => '#31752F',
            'logo' => 'images/microsoft-planner-badge.svg',
            'residency' => ['eu', 'us', 'global'],
            'compliance' => [
                    [
                        'id' => 'iso27001',
                        'label' => ['de' => 'ISO 27001', 'en' => 'ISO 27001'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-ISO-27001',
                        'description' => ['de' => 'Über Microsoft Compliance.', 'en' => 'Via Microsoft compliance.'],
                    ],
                    [
                        'id' => 'c5',
                        'label' => ['de' => 'BSI C5', 'en' => 'BSI C5'],
                        'href' => 'https://learn.microsoft.com/en-us/compliance/regulatory/offering-c5',
                        'description' => ['de' => 'Relevant für Behörden.', 'en' => 'Relevant for public sector.'],
                    ],
            ],
            'help' => [
                    [
                        'label' => ['de' => 'Planner Help', 'en' => 'Planner help'],
                        'href' => 'https://support.microsoft.com/en-us/planner',
                        'description' => ['de' => 'Hilfe zu Microsoft Planner.', 'en' => 'Help for Microsoft Planner.'],
                    ],
                    [
                        'label' => ['de' => 'Planner Docs', 'en' => 'Planner docs'],
                        'href' => 'https://learn.microsoft.com/en-us/microsoft-365/planner/',
                        'description' => ['de' => 'Admin- und Feature-Docs.', 'en' => 'Admin and feature docs.'],
                    ],
            ],
            'governance' => [
                    [
                        'label' => ['de' => 'Planner Admin', 'en' => 'Planner admin'],
                        'href' => 'https://learn.microsoft.com/en-us/microsoft-365/planner/planner-admin',
                        'description' => ['de' => 'Admin-Einstellungen.', 'en' => 'Admin settings.'],
                    ],
                    [
                        'label' => ['de' => 'Data Loss Prevention', 'en' => 'Data loss prevention'],
                        'href' => 'https://learn.microsoft.com/en-us/microsoft-365/compliance/dlp-learn-about-dlp',
                        'description' => ['de' => 'DLP in Microsoft 365.', 'en' => 'DLP in Microsoft 365.'],
                    ],
            ],
            'learning' => [
                    [
                        'label' => ['de' => 'Planner Training', 'en' => 'Planner training'],
                        'href' => 'https://support.microsoft.com/en-us/office/get-started-with-planner-8a511142-1dfc-4f30-9d6f-198f6604ab0c',
                        'description' => ['de' => 'Getting Started.', 'en' => 'Getting started.'],
                    ],
            ],
            'certifications' => [
                    [
                        'label' => ['de' => 'Microsoft 365 Training', 'en' => 'Microsoft 365 training'],
                        'href' => 'https://learn.microsoft.com/en-us/training/microsoft-365/',
                        'description' => ['de' => 'M365 Learn Pfade.', 'en' => 'M365 Learn paths.'],
                    ],
            ],
        ],

    ],
];
