<?php

namespace App\Services\AcademicInsights;

class GeoCatalog
{
    public static function colombiaDepartments(): array
    {
        return [
            'Amazonas' => ['lat' => -1.4429, 'lng' => -71.5724],
            'Antioquia' => ['lat' => 6.2442, 'lng' => -75.5812],
            'Arauca' => ['lat' => 7.0847, 'lng' => -70.7591],
            'Atlántico' => ['lat' => 10.9685, 'lng' => -74.7813],
            'Bogotá D.C.' => ['lat' => 4.7110, 'lng' => -74.0721],
            'Bolívar' => ['lat' => 10.3910, 'lng' => -75.4794],
            'Boyacá' => ['lat' => 5.5353, 'lng' => -73.3678],
            'Caldas' => ['lat' => 5.0703, 'lng' => -75.5138],
            'Caquetá' => ['lat' => 1.6144, 'lng' => -75.6062],
            'Casanare' => ['lat' => 5.3378, 'lng' => -72.3959],
            'Cauca' => ['lat' => 2.4448, 'lng' => -76.6147],
            'Cesar' => ['lat' => 10.4742, 'lng' => -73.2436],
            'Chocó' => ['lat' => 5.6947, 'lng' => -76.6611],
            'Córdoba' => ['lat' => 8.7500, 'lng' => -75.8800],
            'Cundinamarca' => ['lat' => 4.6817, 'lng' => -74.0760],
            'Guainía' => ['lat' => 2.5854, 'lng' => -68.5247],
            'Guaviare' => ['lat' => 2.5729, 'lng' => -72.6459],
            'Huila' => ['lat' => 2.9386, 'lng' => -75.2809],
            'La Guajira' => ['lat' => 11.5444, 'lng' => -72.9072],
            'Magdalena' => ['lat' => 11.2408, 'lng' => -74.1990],
            'Meta' => ['lat' => 4.1420, 'lng' => -73.6266],
            'Nariño' => ['lat' => 1.2136, 'lng' => -77.2811],
            'Norte de Santander' => ['lat' => 7.8939, 'lng' => -72.5078],
            'Putumayo' => ['lat' => 0.4360, 'lng' => -75.5277],
            'Quindío' => ['lat' => 4.5339, 'lng' => -75.6811],
            'Risaralda' => ['lat' => 4.8143, 'lng' => -75.6946],
            'San Andrés y Providencia' => ['lat' => 12.5847, 'lng' => -81.7006],
            'Santander' => ['lat' => 7.1193, 'lng' => -73.1227],
            'Sucre' => ['lat' => 9.3047, 'lng' => -75.3978],
            'Tolima' => ['lat' => 4.4389, 'lng' => -75.2322],
            'Valle del Cauca' => ['lat' => 3.4516, 'lng' => -76.5320],
            'Vaupés' => ['lat' => 1.2520, 'lng' => -70.2340],
            'Vichada' => ['lat' => 5.6947, 'lng' => -67.4859],
        ];
    }

    public static function countries(): array
    {
        return [
            'Alemania' => ['lat' => 51.1657, 'lng' => 10.4515],
            'Argentina' => ['lat' => -38.4161, 'lng' => -63.6167],
            'Australia' => ['lat' => -25.2744, 'lng' => 133.7751],
            'Austria' => ['lat' => 47.5162, 'lng' => 14.5501],
            'Bélgica' => ['lat' => 50.5039, 'lng' => 4.4699],
            'Canadá' => ['lat' => 56.1304, 'lng' => -106.3468],
            'China' => ['lat' => 35.8617, 'lng' => 104.1954],
            'Corea del Sur' => ['lat' => 35.9078, 'lng' => 127.7669],
            'Dinamarca' => ['lat' => 56.2639, 'lng' => 9.5018],
            'España' => ['lat' => 40.4637, 'lng' => -3.7492],
            'Estados Unidos' => ['lat' => 37.0902, 'lng' => -95.7129],
            'Francia' => ['lat' => 46.2276, 'lng' => 2.2137],
            'Holanda' => ['lat' => 52.1326, 'lng' => 5.2913],
            'Irlanda' => ['lat' => 53.4129, 'lng' => -8.2439],
            'Italia' => ['lat' => 41.8719, 'lng' => 12.5674],
            'Japón' => ['lat' => 36.2048, 'lng' => 138.2529],
            'México' => ['lat' => 23.6345, 'lng' => -102.5528],
            'Nueva Zelanda' => ['lat' => -40.9006, 'lng' => 174.8860],
            'Portugal' => ['lat' => 39.3999, 'lng' => -8.2245],
            'Reino Unido' => ['lat' => 55.3781, 'lng' => -3.4360],
            'Singapur' => ['lat' => 1.3521, 'lng' => 103.8198],
            'Suecia' => ['lat' => 60.1282, 'lng' => 18.6435],
            'Suiza' => ['lat' => 46.8182, 'lng' => 8.2275],
        ];
    }
}
