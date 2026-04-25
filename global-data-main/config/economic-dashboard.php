<?php

return [
    'sources' => [
        'world-bank' => [
            'name' => 'World Bank',
            'nav_label' => 'World Bank',
            'path' => '/world-bank',
            'tagline' => 'Global development indicators and long-run macroeconomic series.',
            'focus' => 'Development and macro indicators',
            'description' => 'Best suited for broad cross-country development analysis and widely used macroeconomic indicators.',
            'methodology_notice' => 'Definitions may still differ from IMF, OECD or FRED equivalents even when labels appear similar.',
            'planned_next_step' => 'World Bank is now running on the shared provider architecture introduced in PR3.',
            'coverage_bullets' => ['Country selection', 'Indicator mapping', 'Historical time series', 'Live World Bank data'],
        ],
        'imf' => [
            'name' => 'IMF',
            'nav_label' => 'IMF',
            'path' => '/imf',
            'tagline' => 'Macroeconomic surveillance, fiscal context and international financial datasets.',
            'focus' => 'Macroeconomic and fiscal surveillance',
            'description' => 'Important for inflation, debt and unemployment context where fiscal or macro frameworks matter.',
            'methodology_notice' => 'IMF series can differ from national accounts or other providers depending on estimation and reporting frameworks.',
            'planned_next_step' => 'To be added after the shared provider normalization layer is in place.',
            'coverage_bullets' => ['Inflation context', 'Debt-related proxies', 'Macroeconomic comparisons'],
        ],
        'oecd' => [
            'name' => 'OECD',
            'nav_label' => 'OECD',
            'path' => '/oecd',
            'tagline' => 'Comparable datasets with strong relevance for advanced economies and policy benchmarking.',
            'focus' => 'Policy benchmarking and comparable OECD metrics',
            'description' => 'Useful when the analysis requires more standardized comparisons across OECD member economies.',
            'methodology_notice' => 'Coverage is not as broad for every country or indicator, so gaps should be surfaced clearly.',
            'planned_next_step' => 'Will follow once the provider adapter pattern is stable.',
            'coverage_bullets' => ['Standardized indicator definitions', 'Policy-oriented datasets', 'Structured cross-country views'],
        ],
        'un-data' => [
            'name' => 'UN Data',
            'nav_label' => 'UN Data',
            'path' => '/un-data',
            'tagline' => 'Broad international statistical coverage with useful demographic and social context.',
            'focus' => 'Broad international statistical context',
            'description' => 'Adds value when comparing economic signals with population and broader statistical context.',
            'methodology_notice' => 'Availability and series structure can vary, so normalization and explanatory notes are essential.',
            'planned_next_step' => 'Will be connected after the first provider adapters validate the shared normalization contract.',
            'coverage_bullets' => ['Demographic context', 'Wide international coverage', 'Complementary indicator discovery'],
        ],
        'dbnomics' => [
            'name' => 'DBnomics',
            'nav_label' => 'DBnomics',
            'path' => '/dbnomics',
            'tagline' => 'Unified access layer spanning multiple economic database publishers.',
            'focus' => 'Multi-database discovery and experimentation',
            'description' => 'Helpful for expanding indicator coverage and testing alternative provider pathways.',
            'methodology_notice' => 'Because DBnomics aggregates many publishers, source attribution must remain highly visible.',
            'planned_next_step' => 'DBnomics is now connected through the shared provider architecture using the World Bank WDI dataset via DBnomics.',
            'coverage_bullets' => ['Provider discovery', 'Expanded indicator search', 'Source attribution emphasis', 'Live DBnomics data'],
        ],
        'fred' => [
            'name' => 'FRED',
            'nav_label' => 'FRED',
            'path' => '/fred',
            'tagline' => 'Federal Reserve economic data with strong strength in United States macroeconomic series.',
            'focus' => 'United States and Federal Reserve data',
            'description' => 'Ideal for deep U.S. series and as a reference point when benchmarking against global datasets.',
            'methodology_notice' => 'FRED often republishes third-party series, so original series ownership still needs to be shown explicitly.',
            'planned_next_step' => 'Will be integrated with clear source metadata and fallback behavior.',
            'coverage_bullets' => ['U.S. macro series', 'Detailed historical views', 'Strong source metadata needs'],
        ],
    ],

    'default_countries' => [
        ['code' => 'NZL', 'name' => 'New Zealand'],
        ['code' => 'COL', 'name' => 'Colombia'],
        ['code' => 'USA', 'name' => 'United States'],
        ['code' => 'AUS', 'name' => 'Australia'],
    ],

    'default_indicators' => [
        ['key' => 'gdp', 'label' => 'GDP', 'description' => 'Gross domestic product'],
        ['key' => 'gdp_per_capita', 'label' => 'GDP per capita', 'description' => 'Economic output per resident'],
        ['key' => 'inflation', 'label' => 'Inflation', 'description' => 'Consumer price inflation or closest comparable series'],
        ['key' => 'unemployment', 'label' => 'Unemployment', 'description' => 'Unemployment rate or closest comparable labour market series'],
        ['key' => 'population', 'label' => 'Population', 'description' => 'Total resident population'],
        ['key' => 'public_debt', 'label' => 'Public debt', 'description' => 'Public debt or closest fiscal debt proxy'],
    ],

    'default_year_range' => ['from' => 2014, 'to' => 2024],

    'normalized_model_fields' => [
        'source', 'source_indicator_code', 'source_indicator_name', 'normalized_indicator_key',
        'normalized_indicator_label', 'country_code', 'country_name', 'year', 'value', 'unit', 'notes', 'metadata',
    ],

    'providers' => [
        'world_bank' => [
            'base_url' => 'https://api.worldbank.org/v2', 'source_id' => 2, 'timeout_seconds' => 12,
            'retry_times' => 1, 'retry_sleep_milliseconds' => 200, 'per_page' => 2000, 'cache_ttl_seconds' => 21600,
            'indicator_map' => [
                'gdp' => ['code' => 'NY.GDP.MKTP.CD', 'source_name' => 'GDP (current US$)', 'label' => 'GDP', 'unit' => 'current US$', 'format' => 'currency_compact'],
                'gdp_per_capita' => ['code' => 'NY.GDP.PCAP.CD', 'source_name' => 'GDP per capita (current US$)', 'label' => 'GDP per capita', 'unit' => 'current US$', 'format' => 'currency_standard'],
                'inflation' => ['code' => 'FP.CPI.TOTL.ZG', 'source_name' => 'Inflation, consumer prices (annual %)', 'label' => 'Inflation', 'unit' => 'annual %', 'format' => 'percent'],
                'unemployment' => ['code' => 'SL.UEM.TOTL.ZS', 'source_name' => 'Unemployment, total (% of total labor force) (modeled ILO estimate)', 'label' => 'Unemployment', 'unit' => '% of total labor force', 'format' => 'percent'],
                'population' => ['code' => 'SP.POP.TOTL', 'source_name' => 'Population, total', 'label' => 'Population', 'unit' => 'people', 'format' => 'population'],
                'public_debt' => ['code' => 'GC.DOD.TOTL.GD.ZS', 'source_name' => 'Central government debt, total (% of GDP)', 'label' => 'Public debt', 'unit' => '% of GDP', 'format' => 'percent', 'is_proxy' => true, 'note' => 'Closest available World Bank fiscal debt proxy for the current provider implementation.', 'proxy_message' => 'Public debt is currently represented by the World Bank indicator “Central government debt, total (% of GDP)” as the closest fiscal debt proxy for this provider page.'],
            ],
        ],
        'dbnomics' => [
            'base_url' => 'https://api.db.nomics.world/v22', 'provider_code' => 'WB', 'dataset_code' => 'WDI', 'annual_series_prefix' => 'A-', 'country_separator' => '-',
            'timeout_seconds' => 12, 'retry_times' => 1, 'retry_sleep_milliseconds' => 200, 'cache_ttl_seconds' => 21600,
            'indicator_map' => [
                'gdp' => ['code' => 'NY.GDP.MKTP.CD', 'source_name' => 'GDP (current US$)', 'label' => 'GDP', 'unit' => 'current US$', 'format' => 'currency_compact'],
                'gdp_per_capita' => ['code' => 'NY.GDP.PCAP.CD', 'source_name' => 'GDP per capita (current US$)', 'label' => 'GDP per capita', 'unit' => 'current US$', 'format' => 'currency_standard'],
                'inflation' => ['code' => 'FP.CPI.TOTL.ZG', 'source_name' => 'Inflation, consumer prices (annual %)', 'label' => 'Inflation', 'unit' => 'annual %', 'format' => 'percent'],
                'unemployment' => ['code' => 'SL.UEM.TOTL.ZS', 'source_name' => 'Unemployment, total (% of total labor force) (modeled ILO estimate)', 'label' => 'Unemployment', 'unit' => '% of total labor force', 'format' => 'percent'],
                'population' => ['code' => 'SP.POP.TOTL', 'source_name' => 'Population, total', 'label' => 'Population', 'unit' => 'people', 'format' => 'population'],
                'public_debt' => ['code' => 'GC.DOD.TOTL.GD.ZS', 'source_name' => 'Central government debt, total (% of GDP)', 'label' => 'Public debt', 'unit' => '% of GDP', 'format' => 'percent', 'is_proxy' => true, 'note' => 'Closest available DBnomics fiscal debt proxy for the current provider implementation.', 'proxy_message' => 'Public debt is currently represented through the DBnomics-mapped World Bank WDI debt series as the closest fiscal debt proxy for this provider page.'],
            ],
        ],
    ],
];
