# Simulador de Economía

Aplicación web desarrollada en Laravel para crear escenarios competitivos entre dos empresas, ejecutar simulaciones por periodos y analizar resultados como ventas, market share, profit, HHI y liderazgo de mercado.

## Descripción general

**Simulador de Economía** permite modelar escenarios de competencia entre **Company A** y **Company B** usando variables como:

- precio
- presupuesto de publicidad
- tipo de mercado
- estrategia competitiva
- número de consumidores
- número de periodos

La aplicación ejecuta una simulación basada en utilidades del consumidor y probabilidad de elección, y genera resultados por periodo para cada empresa.

## Funcionalidades principales

- Autenticación de usuarios
- Creación, edición y visualización de escenarios
- Ejecución de simulaciones
- Resultados por periodo
- Visualización de market share, profit, HHI y leader
- Comparación entre escenarios
- Exportación de reportes
- Página de ayuda con interpretación y fórmulas del modelo

## Modelo matemático de la simulación

La lógica principal está implementada en:

- `app/Services/SimulationService.php`

### 1. Utilidad del consumidor

Para cada consumidor y para cada empresa:

```text
price_component = (120 / max(price, 0.01)) * price_weight

advertising_component = log(max(ad_budget, 0) + 1) * 8 * ad_weight

utility = ((price_component + advertising_component) * period_shock) + noise
