Este proyecto consiste en el desarrollo de un Software de Gestión de Fabricación de Concreto y Control de Entradas/Salidas diseñado específicamente para una planta de concreto premezclado. El objetivo es optimizar y digitalizar el control de inventario de materias primas y la producción de concreto.

🏗️ Tecnología y Arquitectura
Base de Datos: PostgreSQL.

Lenguaje de Programación: PHP.

Servidor: Ubuntu.

🛠️ Funcionalidades Clave
1. Control de Entradas (Inventario de Materias Primas)
El sistema deberá registrar y gestionar las entradas de los siguientes insumos con sus respectivas unidades de medida:

Agregados (Grava y Arena): metros cúbicos (m 
3
 ).

Cemento (CPC-40): toneladas (t).

Agua: litros (L).

Aditivos (Líquidos y Sólidos): litros (L) para líquidos y unidades de peso según corresponda (se especificará más adelante).

2. Gestión y Catálogo de Insumos
Capacidad de agregar, editar y eliminar los distintos agregados y aditivos utilizados en la producción.

3. Control de Salidas (Producción de Concreto)
El sistema debe registrar la producción de concreto premezclado cuya unidad de salida es el metro cúbico (m 
3
 ). El consumo de insumos para cada m 
3
  producido estará determinado por la Resistencia requerida del concreto, según las fórmulas estandarizadas en el "Libro de Resistencias" del programa.

Las unidades de consumo/salida de los insumos por cada m 
3
  de concreto fabricado son:

Grava, Arena y Cemento: kilogramos (kg).

Agua: litros (L).

Aditivos Líquidos: mililitros (mL) o decilitros (dL).

Aditivos Sólidos: gramos (g) o kilogramos (kg).

📊 Alcance
El sistema debe proveer una solución robusta para:

Mantener un inventario preciso y en tiempo real de las materias primas.

Automatizar el cálculo de la merma/consumo de insumos basado en las fórmulas de resistencia del concreto producido.

Generar reportes de producción y consumo de materiales.
