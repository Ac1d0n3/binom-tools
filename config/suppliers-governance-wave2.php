<?php

/**
 * Wave 2 governance overlays — ERP/HCM source-native PII, DSDR, skip guidance.
 * Merged onto catalog products by id (sap-s4hana, netsuite, workday, successfactors).
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'sap-s4hana' => [
        'pii' => [
            [
                'entity' => 'KNA1 (Customer)',
                'fields' => ['kunnr', 'name1', 'stras', 'pstlz', 'ort01', 'smtp_addr', 'telf1', 'telf2'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Debitorenstamm — E-Mail/Telefon/Adresse taggen; kunnr als Business-Key behalten.', 'en' => 'Customer master — tag email/phone/address; keep kunnr as business key.'],
            ],
            [
                'entity' => 'LFA1 (Vendor)',
                'fields' => ['lifnr', 'name1', 'stras', 'pstlz', 'smtp_addr', 'telf1'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Kreditoren-Kontakt-PII — eigene Policy vs. Kunden-PII.', 'en' => 'Vendor contact PII — separate policy from customer PII.'],
            ],
            [
                'entity' => 'ADR6 / Communication',
                'fields' => ['smtp_addr', 'tel_number', 'consnumber', 'addrnumber'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Zentrale Kommunikationsdaten — oft zweite PII-Fläche neben KNA1/LFA1.', 'en' => 'Central communication data — often a second PII surface beside KNA1/LFA1.'],
            ],
            [
                'entity' => 'USR21 / SAP User',
                'fields' => ['bname', 'persnumber', 'addrnumber', 'smtp_addr'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'SAP-Anwender und technische User — Workforce vs. Integration-User trennen.', 'en' => 'SAP users and technical accounts — separate workforce vs integration users.'],
            ],
            [
                'entity' => 'VBAK / VBAP (Order texts)',
                'fields' => ['bstnk', 'ihrez', 'text lines (STXH/STXL)'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Auftragstexte und Kundenreferenzen können Personenbezug haben.', 'en' => 'Order texts and customer references can identify persons.'],
            ],
            [
                'entity' => 'Custom Z-fields',
                'fields' => ['Z* email/phone fields', 'Z* external id / national id'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Alle Z-Felder mit E-Mail/External Id via Data Dictionary inventarisieren.', 'en' => 'Inventory all Z-fields with email/external id via Data Dictionary.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'kunnr, lifnr, matnr, smtp_addr, telf1, External Id (Z-Felder), bukrs.', 'en' => 'kunnr, lifnr, matnr, smtp_addr, telf1, external id (Z-fields), bukrs.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'KNA1, LFA1, ADR6 — plus jedes Objekt mit Kontakt-/Adressfeldern.', 'en' => 'KNA1, LFA1, ADR6 — plus any object with contact/address fields.'],
            ],
            [
                'focus' => ['de' => 'Warehouse-Kopien', 'en' => 'Warehouse copies'],
                'notes' => ['de' => 'CDS/BW-Extracts, SLT/Replication, Datasphere, Reverse-ETL und BI-Shared-Stages.', 'en' => 'CDS/BW extracts, SLT/replication, Datasphere, reverse-ETL and BI shared stages.'],
            ],
            [
                'focus' => ['de' => 'Change documents (CDHDR/CDPOS)', 'en' => 'Change documents (CDHDR/CDPOS)'],
                'notes' => ['de' => 'Historische Feldwerte bleiben PII — Retention vor Load klären.', 'en' => 'Historical field values remain PII — clarify retention before load.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Change documents (CDHDR/CDPOS)',
                'category' => 'system',
                'reason' => ['de' => 'Änderungsprotokolle — hohes Volumen, selten KPI-relevant.', 'en' => 'Change documents — high volume, rarely KPI-relevant.'],
            ],
            [
                'name' => 'IDoc archives (EDIDC / EDID4 / EDIDS)',
                'category' => 'system',
                'reason' => ['de' => 'Integrations-Payloads — technisches Rauschen, kein Mart-Kern.', 'en' => 'Integration payloads — technical noise, not mart core.'],
            ],
            [
                'name' => 'Application logs (SLG1 / BAL)',
                'category' => 'system',
                'reason' => ['de' => 'Runtime- und Debug-Logs — nicht für Business-KPIs.', 'en' => 'Runtime and debug logs — not for business KPIs.'],
            ],
            [
                'name' => 'Spool / print queue tables',
                'category' => 'system',
                'reason' => ['de' => 'Druck-Spool — kein Analytics-Load.', 'en' => 'Print spool — not an analytics load.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'Change document tables (bulk)',
                'reason' => ['de' => 'Hohes Volumen ohne KPI-Nutzen.', 'en' => 'High volume without KPI value.'],
            ],
            [
                'name' => 'IDoc payload archives',
                'reason' => ['de' => 'Technisches Rauschen und Partner-PII in Segmenten.', 'en' => 'Technical noise and partner PII in segments.'],
            ],
            [
                'name' => 'Application / job logs',
                'reason' => ['de' => 'Nicht analytisch.', 'en' => 'Not analytical.'],
            ],
            [
                'name' => 'Unused Z-fields (bulk sync all)',
                'reason' => ['de' => 'Vergrößert DSDR-Fläche und Kosten.', 'en' => 'Expands DSDR surface and cost.'],
            ],
            [
                'name' => 'Long text (STXH/STXL) bodies',
                'reason' => ['de' => 'Freitext mit PII — nur mit Use Case.', 'en' => 'Free text with PII — only with a use case.'],
            ],
        ],
    ],

    'netsuite' => [
        'pii' => [
            [
                'entity' => 'customer',
                'fields' => ['email', 'phone', 'altphone', 'firstname', 'lastname', 'companyname', 'addressbook'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Debitoren/Kunden — E-Mail und Adressbuch inventarisieren.', 'en' => 'Customers — inventory email and address book.'],
            ],
            [
                'entity' => 'vendor',
                'fields' => ['email', 'phone', 'altphone', 'companyname', 'addressbook'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Lieferanten-Kontakt-PII — getrennt von Kunden-PII.', 'en' => 'Vendor contact PII — separate from customer PII.'],
            ],
            [
                'entity' => 'employee',
                'fields' => ['email', 'phone', 'firstname', 'lastname', 'socialsecuritynumber', 'birthdate'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'HR/Workforce — SSN/DOB nur mit Legal-Basis und engem Zugriff.', 'en' => 'HR/workforce — SSN/DOB only with legal basis and tight access.'],
            ],
            [
                'entity' => 'contact',
                'fields' => ['email', 'phone', 'firstname', 'lastname', 'entityid'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Kontakte an Customer/Vendor — zweite Personen-Fläche.', 'en' => 'Contacts on customer/vendor — second person surface.'],
            ],
            [
                'entity' => 'systemnote / transaction body',
                'fields' => ['note', 'memomain', 'message', 'comments'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'System Notes und Memo-Felder — ungeprüfte PII in Transaktionen.', 'en' => 'System notes and memo fields — unchecked PII in transactions.'],
            ],
            [
                'entity' => 'Custom fields / records',
                'fields' => ['custentity_* email/phone', 'custbody_* PII customs'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Records Catalog + SuiteQL Metadata für Custom Fields scannen.', 'en' => 'Scan Records Catalog + SuiteQL metadata for custom fields.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'internalid, entityid, email, phone, externalid customs, subsidiary.', 'en' => 'internalid, entityid, email, phone, externalid customs, subsidiary.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'customer, vendor, employee, contact + transaction.entity.', 'en' => 'customer, vendor, employee, contact + transaction.entity.'],
            ],
            [
                'focus' => ['de' => 'SuiteQL / Saved Search exports', 'en' => 'SuiteQL / saved search exports'],
                'notes' => ['de' => 'Ad-hoc SuiteQL und Saved Searches kopieren PII in Warehouse-Stages.', 'en' => 'Ad-hoc SuiteQL and saved searches copy PII into warehouse stages.'],
            ],
            [
                'focus' => ['de' => 'Sandbox vs Production', 'en' => 'Sandbox vs production'],
                'notes' => ['de' => 'Sandbox-Refreshs nicht mit Prod-Marts mischen.', 'en' => 'Do not mix sandbox refreshes with prod marts.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'systemnote (full dump)',
                'category' => 'system',
                'reason' => ['de' => 'Audit-Noise und Freitext-PII — gezielt syncen.', 'en' => 'Audit noise and free-text PII — sync selectively.'],
            ],
            [
                'name' => 'file cabinet / media item binaries',
                'category' => 'system',
                'reason' => ['de' => 'Binaries — teuer, wenig Analytics-Nutzen.', 'en' => 'Binaries — expensive, little analytics value.'],
            ],
            [
                'name' => 'script / deployment / execution log bulk',
                'category' => 'system',
                'reason' => ['de' => 'Plattform-Runtime — kein Mart-Kern.', 'en' => 'Platform runtime — not mart core.'],
            ],
            [
                'name' => 'workflow history (ohne Bedarf)',
                'category' => 'system',
                'reason' => ['de' => 'Ops-Noise — nur bei Prozess-Audit laden.', 'en' => 'Ops noise — load only for process audit.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'System note / memo free-text (bulk)',
                'reason' => ['de' => 'PII — gezielt oder gar nicht.', 'en' => 'PII — selectively or not at all.'],
            ],
            [
                'name' => 'Unused custom fields (bulk sync all)',
                'reason' => ['de' => 'DSDR-Fläche und Sync-Kosten.', 'en' => 'DSDR surface and sync cost.'],
            ],
            [
                'name' => 'File cabinet binary content',
                'reason' => ['de' => 'Kosten ohne KPI-Nutzen.', 'en' => 'Cost without KPI value.'],
            ],
            [
                'name' => 'Formula-only fields without consumer',
                'reason' => ['de' => 'Abgeleitet — in Curated neu berechnen.', 'en' => 'Derived — recompute in Curated.'],
            ],
            [
                'name' => 'Debug / test entity records',
                'reason' => ['de' => 'Verschmutzt Prod-Marts.', 'en' => 'Pollutes prod marts.'],
            ],
        ],
    ],

    'workday' => [
        'pii' => [
            [
                'entity' => 'Worker',
                'fields' => ['workerId', 'employeeId', 'email', 'primaryWorkEmail', 'legalName', 'preferredName', 'dateOfBirth'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Kern-Workforce-PII — eigene Retention und Zugriff vs. Finance-Facts.', 'en' => 'Core workforce PII — separate retention and access vs finance facts.'],
            ],
            [
                'entity' => 'Worker national ID',
                'fields' => ['nationalId', 'nationalIdType', 'governmentId', 'passportNumber'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Hochsensible IDs — Default skip oder Hash; Legal-Basis dokumentieren.', 'en' => 'Highly sensitive IDs — default skip or hash; document legal basis.'],
            ],
            [
                'entity' => 'Payment Election / Bank',
                'fields' => ['bankAccountNumber', 'iban', 'routingNumber', 'accountType'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Bankdaten — nie in Analytics-Marts ohne explizite Freigabe.', 'en' => 'Bank data — never in analytics marts without explicit approval.'],
            ],
            [
                'entity' => 'Person / Contact',
                'fields' => ['homeAddress', 'homePhone', 'personalEmail', 'emergencyContact'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Private Kontaktdaten — strikt von Business-Email trennen.', 'en' => 'Private contact data — strictly separate from business email.'],
            ],
            [
                'entity' => 'RaaS report columns',
                'fields' => ['custom report columns with PII', 'SSN/DOB exports'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'RaaS kann versteckte PII-Spalten liefern — Report-Definition reviewen.', 'en' => 'RaaS can deliver hidden PII columns — review report definition.'],
            ],
            [
                'entity' => 'Integration System User (ISU)',
                'fields' => ['ISU credentials', 'token payloads', 'worker extracts with PII'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'ISU sieht breiter als UI-Rollen — Allowlist vor Load.', 'en' => 'ISU sees broader than UI roles — allowlist before load.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'workerId, employeeId, email, nationalId (hashed), positionId, costCenter.', 'en' => 'workerId, employeeId, email, nationalId (hashed), positionId, costCenter.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Worker, Person, Job Profile, Position, Cost Center + RaaS-Kopien.', 'en' => 'Worker, person, job profile, position, cost center + RaaS copies.'],
            ],
            [
                'focus' => ['de' => 'RaaS / EIB / Studio exports', 'en' => 'RaaS / EIB / Studio exports'],
                'notes' => ['de' => 'Jeder Report-Export ist eine DSDR-Kopie — Spalten-Allowlist pflegen.', 'en' => 'Every report export is a DSDR copy — maintain column allowlists.'],
            ],
            [
                'focus' => ['de' => 'Implementation / sandbox tenants', 'en' => 'Implementation / sandbox tenants'],
                'notes' => ['de' => 'Sandbox-Worker nicht mit Prod-Headcount mischen.', 'en' => 'Do not mix sandbox workers with prod headcount.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'Bank / payment election detail',
                'category' => 'system',
                'reason' => ['de' => 'Hochsensible Finanz-PII — out of scope für Standard-Marts.', 'en' => 'Highly sensitive financial PII — out of scope for standard marts.'],
            ],
            [
                'name' => 'Audit / security event logs (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Security-Telemetrie — nicht Business-KPI.', 'en' => 'Security telemetry — not business KPI.'],
            ],
            [
                'name' => 'Attachment / document binaries',
                'category' => 'system',
                'reason' => ['de' => 'Binaries und HR-Dokumente — teuer und sensibel.', 'en' => 'Binaries and HR documents — expensive and sensitive.'],
            ],
            [
                'name' => 'Full RaaS dumps without allowlist',
                'category' => 'system',
                'reason' => ['de' => 'Versteckte PII-Spalten und Volumen.', 'en' => 'Hidden PII columns and volume.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'nationalId / governmentId cleartext',
                'reason' => ['de' => 'Default skip oder Hash — Legal-Basis erforderlich.', 'en' => 'Default skip or hash — legal basis required.'],
            ],
            [
                'name' => 'Bank account numbers',
                'reason' => ['de' => 'Nie in Analytics ohne Freigabe.', 'en' => 'Never in analytics without approval.'],
            ],
            [
                'name' => 'Home address / personal email (bulk)',
                'reason' => ['de' => 'Private PII — nur mit Use Case.', 'en' => 'Private PII — only with a use case.'],
            ],
            [
                'name' => 'Unused custom report columns',
                'reason' => ['de' => 'DSDR ohne Consumer.', 'en' => 'DSDR without consumers.'],
            ],
            [
                'name' => 'Implementation test workers',
                'reason' => ['de' => 'Prod-Marts sauber halten.', 'en' => 'Keep prod marts clean.'],
            ],
        ],
    ],

    'successfactors' => [
        'pii' => [
            [
                'entity' => 'PerPerson',
                'fields' => ['personIdExternal', 'dateOfBirth', 'placeOfBirth', 'nationalId', 'countryOfBirth'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Personenstamm — nationalId/DOB nur mit Legal-Basis.', 'en' => 'Person master — nationalId/DOB only with legal basis.'],
            ],
            [
                'entity' => 'PerPersonal / PerEmail',
                'fields' => ['firstName', 'lastName', 'middleName', 'emailAddress', 'phoneNumber'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Workforce-Kontakt — Business-Email von privatem trennen.', 'en' => 'Workforce contact — separate business from private email.'],
            ],
            [
                'entity' => 'EmpEmployment',
                'fields' => ['userId', 'personIdExternal', 'startDate', 'endDate', 'originalStartDate'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Employment-Grain für Headcount — PII nur über Person-Join.', 'en' => 'Employment grain for headcount — PII only via person join.'],
            ],
            [
                'entity' => 'EmpJob',
                'fields' => ['userId', 'department', 'managerId', 'jobTitle', 'costCenter'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Org-Struktur — managerId erzeugt Workforce-Graph.', 'en' => 'Org structure — managerId creates workforce graph.'],
            ],
            [
                'entity' => 'MDF custom objects',
                'fields' => ['cust_* email/phone/nationalId', 'externalCode with PII'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'MDF-Objekte via OData $metadata und Admin Center inventarisieren.', 'en' => 'Inventory MDF objects via OData $metadata and Admin Center.'],
            ],
            [
                'entity' => 'OData sensitive fields',
                'fields' => ['PerNationalId', 'PerAddressDEFLT', 'Background_* text fields'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'OData-Expand kann versteckte PII liefern — $select-Allowlist.', 'en' => 'OData expand can deliver hidden PII — $select allowlist.'],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'userId, personIdExternal, emailAddress, nationalId (hashed), externalCode.', 'en' => 'userId, personIdExternal, emailAddress, nationalId (hashed), externalCode.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'PerPerson, EmpEmployment, EmpJob, PerEmail + MDF-Kopien.', 'en' => 'PerPerson, EmpEmployment, EmpJob, PerEmail + MDF copies.'],
            ],
            [
                'focus' => ['de' => 'OData / Integration Center exports', 'en' => 'OData / Integration Center exports'],
                'notes' => ['de' => 'API-Extracts, CPI-Flows und BI-Connectors verdoppeln Workforce-PII.', 'en' => 'API extracts, CPI flows and BI connectors duplicate workforce PII.'],
            ],
            [
                'focus' => ['de' => 'Picklist / BC snapshots', 'en' => 'Picklist / BC snapshots'],
                'notes' => ['de' => 'Business Configuration und Picklists — keine PII, aber DSDR-Fläche bei Bulk-Dumps.', 'en' => 'Business configuration and picklists — no PII, but DSDR surface on bulk dumps.'],
            ],
        ],
        'skipTables' => [
            [
                'name' => 'PerNationalId (cleartext bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Hochsensible IDs — Default skip oder Hash.', 'en' => 'Highly sensitive IDs — default skip or hash.'],
            ],
            [
                'name' => 'Background / attachment document blobs',
                'category' => 'system',
                'reason' => ['de' => 'HR-Dokumente und Freitext — teuer und sensibel.', 'en' => 'HR documents and free text — expensive and sensitive.'],
            ],
            [
                'name' => 'Audit / change log bulk (ohne Bedarf)',
                'category' => 'system',
                'reason' => ['de' => 'Historische PII-Werte — Retention vor Load.', 'en' => 'Historical PII values — retention before load.'],
            ],
            [
                'name' => 'Unused MDF objects (full sync)',
                'category' => 'system',
                'reason' => ['de' => 'Schema-Ballast ohne Consumer.', 'en' => 'Schema ballast without consumers.'],
            ],
        ],
        'skip' => [
            [
                'name' => 'nationalId cleartext in marts',
                'reason' => ['de' => 'Hash oder skip — Legal-Basis erforderlich.', 'en' => 'Hash or skip — legal basis required.'],
            ],
            [
                'name' => 'OData $expand without allowlist',
                'reason' => ['de' => 'Versteckte PII-Felder.', 'en' => 'Hidden PII fields.'],
            ],
            [
                'name' => 'Unused MDF custom fields (bulk)',
                'reason' => ['de' => 'DSDR-Fläche.', 'en' => 'DSDR surface.'],
            ],
            [
                'name' => 'Background text / notes free-text',
                'reason' => ['de' => 'Unstrukturierte PII.', 'en' => 'Unstructured PII.'],
            ],
            [
                'name' => 'Sandbox / test person records',
                'reason' => ['de' => 'Prod-Headcount sauber halten.', 'en' => 'Keep prod headcount clean.'],
            ],
        ],
    ],
];
