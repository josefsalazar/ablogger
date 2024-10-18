<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

import { Head, usePage } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Instructions" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Instructions
            </h2>
        </template>
        
        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div
                id="content"
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 p-8"
                >
                
                    <div class="p-2 text-gray-900 dark:text-gray-100">
                        <h1 class="text-2xl font-semibold text-center mb-8 dark:text-white">Guía Completa para Usar la Herramienta de Generación de Artículos</h1>
                    <h2>1. Configuración de la Clave API</h2>
                    <p>Antes de generar cualquier artículo, asegúrate de haber configurado tu clave API en tu <Link href="/profile" class="text-blue-500 hover:underline">perfil</Link>. La herramienta no puede generar contenido sin una clave API válida.</p>

                    <h2>2. Entrada de Palabra Clave (Keyword)</h2>
                    <p>El campo de <strong>Keyword</strong> es el tema principal de tu artículo. Esta palabra o frase debe estar relacionada con el contenido que quieres generar.</p>

                    <h3>2.1 Cómo Introducir la Keyword</h3>
                    <p>Introduce tu palabra clave en el campo de texto <strong>Keyword</strong>. Asegúrate de que sea específica para obtener un contenido más enfocado.</p>

                    <h3>2.2 Ejemplo</h3>
                    <p>Si estás creando un artículo sobre <strong>terrarios</strong>, puedes usar una palabra clave como <strong>"cómo crear un terrario"</strong>.</p>

                    <h2>3. Encabezados (H2 y H3)</h2>
                    <p>La herramienta permite agregar encabezados <strong>H2</strong> y <strong>H3</strong> para estructurar el artículo. Los H2 son secciones principales y los H3 son subsecciones que profundizan en cada H2.</p>

                    <h3>3.1 Añadir Títulos H2</h3>
                    <p>Puedes añadir un H2 al hacer clic en el botón "Añadir H2". El H2 debe introducir una nueva sección en tu artículo.</p>

                    <h3>3.2 Añadir Subencabezados H3</h3>
                    <p>Cada H2 puede tener varios H3. Para agregar un H3, haz clic en "Añadir H3" debajo del H2 correspondiente.</p>

                    <h3>3.3 Ejemplo de Estructura</h3>
                    <p>Para un artículo sobre <strong>terrarios</strong>, la estructura de H2 y H3 podría ser:</p>
                    <ul>
                        <li><strong>H2:</strong> ¿Qué es un terrario?
                            <ul>
                                <li><strong>H3:</strong> Definición</li>
                                <li><strong>H3:</strong> Tipos de terrarios</li>
                                <li><strong>H3:</strong> Beneficios</li>
                            </ul>
                        </li>
                        <li><strong>H2:</strong> Cómo hacer un terrario
                            <ul>
                                <li><strong>H3:</strong> Materiales necesarios</li>
                                <li><strong>H3:</strong> Pasos a seguir</li>
                            </ul>
                        </li>
                    </ul>

                    <h2>4. Palabras Clave Adicionales</h2>
                    <p>Puedes añadir <strong>palabras clave adicionales</strong> para hacer que el contenido sea más específico. Estas palabras clave se usarán para enriquecer el artículo.</p>

                    <h3>4.1 Cómo Usar Palabras Clave Adicionales</h3>
                    <p>Introduce palabras clave adicionales en el campo correspondiente. Pueden ser frases relacionadas o términos relevantes para el tema principal.</p>

                    <h3>4.2 Ejemplo</h3>
                    <p>Si tu palabra clave principal es <strong>"terrarios"</strong>, las palabras clave adicionales podrían ser:</p>
                    <ul>
                        <li>Plantas para terrarios</li>
                        <li>Terrarios DIY</li>
                        <li>Mantenimiento de terrarios</li>
                    </ul>

                    <h2>5. Selección de Estilo y Lenguaje</h2>
                    <p>La herramienta te permite seleccionar el <strong>estilo</strong> y el <strong>lenguaje</strong> del artículo.</p>

                    <h3>5.1 Estilo</h3>
                    <p>Puedes seleccionar entre varios estilos:</p>
                    <ul>
                        <li><strong>Profesional:</strong> Un tono formal y estructurado.</li>
                        <li><strong>Casual:</strong> Un tono más relajado y conversacional.</li>
                    </ul>

                    <h3>5.2 Lenguaje</h3>
                    <p>Actualmente, puedes elegir entre:</p>
                    <ul>
                        <li><strong>Inglés</strong></li>
                        <li><strong>Español</strong></li>
                    </ul>

                    <h2>6. Longitud del Artículo</h2>
                    <p>El parámetro de <strong>Longitud del Artículo</strong> te permite controlar la extensión del contenido. Puedes seleccionar un valor entre 1 y 5, donde 1 es un artículo corto y 5 es un artículo largo y detallado.</p>

                    <h2>7. Editor de Contenidos</h2>
                    <p>La herramienta incluye un <strong>editor de texto enriquecido</strong> que te permite visualizar y editar el contenido generado antes de finalizarlo. Puedes usar funciones básicas de formato como negritas, cursivas, encabezados y listas.</p>

                    <h2>8. Modo de Entrada JSON</h2>
                    <p>Si prefieres trabajar con un formato estructurado, puedes cambiar al <strong>Modo JSON</strong>. Aquí, puedes pegar una estructura JSON con H2 y H3 que luego será procesada y convertida en secciones de texto.</p>

                    <h3>8.1 Ejemplo de JSON Estructurado</h3>
                    <pre>
                    [
                        {
                            "h2": "¿Qué es un Terrario?",
                            "h3": [
                                "Definición",
                                "Beneficios de los terrarios",
                                "Tipos de terrarios"
                            ]
                        },
                        {
                            "h2": "Cómo Hacer un Terrario",
                            "h3": [
                                "Materiales necesarios",
                                "Pasos a seguir"
                            ]
                        }
                    ]
                    </pre>

                    <h2>Conclusión</h2>
                    <p>Siguiendo esta guía, podrás usar todas las características de la herramienta para generar artículos bien estructurados y optimizados según tus necesidades. Puedes alternar entre entradas manuales o en JSON, seleccionar el estilo, el idioma y la longitud del contenido para obtener el mejor resultado posible.</p>
                    </div>
                </div>
            </div>
        </div>
               
            
            
    
    </AuthenticatedLayout>

</template>
<style scoped>
#content * + * {
    line-height: 1.6;
}

#content h1,
#content h2,
#content h3,
#content h4,
#content h5,
#content h6 {
    padding-block: 1em;
    font-weight: bold;
}

#content h1 {
    font-size: 2rem;
}

#content h2 {
    font-size: 1.75rem;
}

#content h3 {
    font-size: 1.65rem;
}

#content h4 {
    font-size: 1.5rem;
}

#content h5 {
    font-size: 1.25rem;
}

#content h6 {
    font-size: 1rem;
}

</style>
