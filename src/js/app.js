let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;
const cita = {
  nombre: '',
  fecha: '',
  hora: '',
  servicios: [],
};

document.addEventListener('DOMContentLoaded', function () {
  iniciarApp();
  //section - Inicio boton autollenar
  // Agregar botón que solo llena campos (no toca nombre) y selecciona 1-4 servicios al azar
  const formulario = document.querySelector('.formulario');
  if (formulario) {
    const btnAuto = document.createElement('button');
    btnAuto.type = 'button'; // evitar submit
    btnAuto.textContent = 'Autollenar';
    btnAuto.classList.add('boton');
    formulario.appendChild(btnAuto);

    btnAuto.addEventListener('click', function () {
      // Fecha: siguiente día hábil
      const fecha = new Date();
      fecha.setDate(fecha.getDate() + 1);
      while ([0, 6].includes(fecha.getUTCDay())) {
        fecha.setDate(fecha.getDate() + 1);
      }
      const fechaFormateada = fecha.toISOString().split('T')[0];
      const inputFecha = document.querySelector('#fecha');
      if (inputFecha) {
        inputFecha.value = fechaFormateada;
        cita.fecha = fechaFormateada;
      }

      // Hora: valor fijo (puedes cambiarlo si quieres aleatorio)
      const inputHora = document.querySelector('#hora');
      if (inputHora) {
        inputHora.value = '14:00';
        cita.hora = '14:00';
      }

      // Seleccionar entre 1 y 4 servicios al azar
      const servicios = Array.from(document.querySelectorAll('.servicio'));
      if (servicios.length === 0) return;

      // Deseleccionar cualquiera previamente seleccionado
      servicios.forEach((s) => {
        if (s.classList.contains('seleccionado')) s.click();
      });

      const maxSeleccion = Math.min(4, servicios.length);
      const cantidad = Math.floor(Math.random() * maxSeleccion) + 1; // 1..maxSeleccion

      // Generar índices aleatorios únicos
      const indices = servicios.map((_, i) => i);
      // Fisher-Yates shuffle
      for (let i = indices.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [indices[i], indices[j]] = [indices[j], indices[i]];
      }

      const seleccionados = indices.slice(0, cantidad);
      seleccionados.forEach((idx) => servicios[idx].click());
    });
  }
  //!section - FIN boton autollenar
});

function iniciarApp() {
  mostrarSeccion(); // muestra y oculta las secciones
  tabs(); /* Cambia la seccion cuando se presionen los tabs */
  botonesPaginador(); // Agrega o quita los botones de la paginacion
  paginaAnterior();
  paginaSiguiente();
  consultarAPI();
  nombreCliente(); //Añade el nobre del cliente al objeto de cita
  seleccionarFecha(); //Anade la fecha al objeto de cita
  seleccionarHora(); //Anade la hora de la cita en el objeto
  mostrarResumen(); //Muestra el resumen de la cita
}
function mostrarSeccion() {
  //Ocultar la seccion anterior
  const seccionAnterior = document.querySelector('.mostrar');

  if (seccionAnterior) {
    seccionAnterior.classList.remove('mostrar');
  }
  //Seleccionar la seccion con el paso...
  const pasoSelector = `#paso-${paso}`;
  const seccion = document.querySelector(pasoSelector);
  seccion.classList.add('mostrar');

  //Quita la clase de actual al tab anterior
  const tabAnterior = document.querySelector('.actual');
  if (tabAnterior) {
    tabAnterior.classList.remove('actual');
  }

  //Resalta el tab actual
  const tab = document.querySelector(`[data-paso="${paso}"]`);
  tab.classList.add('actual');
}

function tabs() {
  const botones = document.querySelectorAll('.tabs button');
  botones.forEach((boton) => {
    boton.addEventListener('click', function (e) {
      paso = parseInt(e.target.dataset.paso);
      mostrarSeccion();
      botonesPaginador();
    });
  });
}

function botonesPaginador() {
  const paginaAnterior = document.querySelector('#anterior');
  const paginaSiguiente = document.querySelector('#siguiente');

  if (paso === 1) {
    paginaAnterior.classList.add('ocultar');
    paginaSiguiente.classList.remove('ocultar');
  } else if (paso === 3) {
    paginaAnterior.classList.remove('ocultar');
    paginaSiguiente.classList.add('ocultar');
    mostrarResumen();
  } else {
    paginaAnterior.classList.remove('ocultar');
    paginaSiguiente.classList.remove('ocultar');
  }
  mostrarSeccion();
}

function paginaAnterior() {
  const paginaAnterior = document.querySelector('#anterior');
  paginaAnterior.addEventListener('click', function () {
    if (paso <= pasoInicial) {
      return;
    }

    paso--;

    botonesPaginador();
  });
}

function paginaSiguiente() {
  const paginaSiguiente = document.querySelector('#siguiente');
  paginaSiguiente.addEventListener('click', function () {
    if (paso >= pasoFinal) {
      return;
    }
    paso++;

    botonesPaginador();
  });
}

async function consultarAPI() {
  try {
    const url = 'http://localhost:3000/api/servicios';
    const resultado = await fetch(url);
    const servicios = await resultado.json();
    mostrarServicios(servicios);
  } catch (error) {
    console.log(error);
  }
}
function mostrarServicios(servicios) {
  servicios.forEach((servicio) => {
    const { id, nombre, precio } = servicio;

    const nombreServicio = document.createElement('P');
    nombreServicio.classList.add('nombre-servicio');
    nombreServicio.textContent = nombre;

    const precioServicio = document.createElement('P');
    precioServicio.classList.add('precio-servicio');
    precioServicio.textContent = `$${precio}`;

    const servicioDiv = document.createElement('DIV');
    servicioDiv.classList.add('servicio');
    servicioDiv.dataset.idServicio = id;

    servicioDiv.onclick = function () {
      seleccionarServicio(servicio);
    };

    servicioDiv.appendChild(nombreServicio);
    servicioDiv.appendChild(precioServicio);

    document.querySelector('#servicios').appendChild(servicioDiv);
  });
}

function seleccionarServicio(servicio) {
  const { id } = servicio;
  const { servicios } = cita;
  //Identificar al servicio seleccionado
  const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);
  //Comprobar si el servicio ya esta agregado
  if (servicios.some((agregado) => agregado.id === id)) {
    //Elimina el servicio
    cita.servicios = servicios.filter((agregado) => agregado.id !== id);
    divServicio.classList.remove('seleccionado');
  } else {
    //Agrega el nuevo servicio
    cita.servicios = [...servicios, servicio];
    divServicio.classList.add('seleccionado');
  }

  /*   console.log(cita); */
}

function nombreCliente() {
  cita.nombre = document.querySelector('#nombre').value;
}

function seleccionarFecha() {
  const inputFecha = document.querySelector('#fecha');
  inputFecha.addEventListener('input', function (e) {
    const dia = new Date(e.target.value).getUTCDay();
    if ([0, 6].includes(dia)) {
      e.target.value = '';
      mostrarAlerta('Fines de semana no se aceptan citas', 'error', '#paso-2 p'); //#paso-2 p puede ser
    } else {
      cita.fecha = e.target.value;
    }
  });
}

function seleccionarHora() {
  const inputHora = document.querySelector('#hora');
  inputHora.addEventListener('input', function (e) {
    const horaCita = e.target.value;
    const hora = horaCita.split(':')[0];
    if (hora < 10 || hora > 18) {
      e.target.value = '';
      mostrarAlerta('Hora no disponible', 'error', '#paso-2 p'); //#paso-2 p puede ser
    } else {
      cita.hora = e.target.value;
    }
  });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
  //previene que se creen multiples alertas
  const alertaPrevia = document.querySelector('.alerta');
  if (alertaPrevia) {
    alertaPrevia.remove();
  }
  // Script para crear la alerta
  const alerta = document.createElement('DIV');
  alerta.textContent = mensaje;
  alerta.classList.add('alerta', tipo);

  const referencia = document.querySelector(elemento);
  referencia.appendChild(alerta);
  if (desaparece) {
    //Eliminar la alerta
    setTimeout(() => {
      alerta.remove();
    }, 2000);
  }
}

function mostrarResumen() {
  const resumen = document.querySelector('.contenido-resumen');
  //Limpiar el Contenido de Resumen
  while (resumen.firstChild) {
    resumen.removeChild(resumen.firstChild);
  }
  if (Object.values(cita).includes('') || cita.servicios.length === 0) {
    mostrarAlerta('Faltan datos de servicios o cita', 'error', '.contenido-resumen', false);
    return;
  }

  //Formatear el div de resumen
  const { nombre, fecha, hora, servicios } = cita;
  const nombreCliente = document.createElement('P');
  nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

  const fechaCita = document.createElement('P');
  fechaCita.innerHTML = `<span>Fecha:</span> ${fecha}`;

  const horaCita = document.createElement('P');
  horaCita.innerHTML = `<span>Hora:</span> ${hora}`;

  servicios.forEach((servicio) => {
    const { id, nombre, precio } = servicio;
    const contenedorServicio = document.createElement('DIV');
    contenedorServicio.classList.add('contenedor-servicio');

    const textoServicio = document.createElement('P');
    textoServicio.textContent = nombre;

    const precioServicio = document.createElement('P');
    precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

    contenedorServicio.appendChild(textoServicio);
    contenedorServicio.appendChild(precioServicio);
    resumen.appendChild(contenedorServicio);
  });

  resumen.appendChild(nombreCliente);
  resumen.appendChild(fechaCita);
  resumen.appendChild(horaCita);
}
