import React, { useEffect, useState } from 'react';
import { getUsuarios } from '../../services/usuarioService';
import { getEstadoEquipos } from '../../services/estadoEquipoService';
import { getMarcas } from '../../services/marcaService';
import { getTipoEquipos } from '../../services/tipoEquipoService';
import { postInventarios} from '../../services/inventarioService';
import Swal from 'sweetalert2';

export const InventarioCrear = ({ handleOpenModal, listarInventarios }) => {

  const [usuarios, setUsuarios]  = useState([]);
  const [marcas, setMarcas] = useState([]);
  const [tipos, setTipos] = useState([]);
  const [estados, setEstados]= useState([]);

  const [valoresform, setValoresform] = useState({});
  const {serial='', modelo='', descripcion='', color='', foto='', fechaCompra='', precio='', usuario, marca, estadoEquipo, tipoEquipo} = valoresform;

  const listarUsuarios = async () => {
    try {
      const { data } = await getUsuarios();
      setUsuarios(data);
    } catch (error) {
      console.log("ocurrio un error");
    }
  }
  useEffect(() => {
    listarUsuarios();
    console.log(getUsuarios());
  }, []);

  const listarMarcas = async () => {
    try {
      const { data } = await getMarcas();
      setMarcas(data);
    } catch (error) {
      console.log(error);
    }
  }
  useEffect(() => {
  listarMarcas();
  }, []);

  const listarEstados = async () => {
    try {
      const { data } = await getEstadoEquipos();
      setEstados(data);
    } catch (error) {
      console.log(error);
    }
  }
  useEffect(() => {
  listarEstados();
  }, []);

  const listarTipos = async () => {
    try {
      const { data } = await getTipoEquipos();
      setTipos(data);
    } catch (error) {
      console.log(error);
    }
  }
  useEffect(() => {
  listarTipos();
  }, []);

  const handleOnChange = ({target}) => {
    const {name, value} = target;
    setValoresform({...valoresform, [name]:value});
  }

  const handleOnSubmit = async (e) => {
    e.preventDefault();
    const inventarioModel = {
      serial, modelo, descripcion, foto, color, fechaCompra, precio,
      usuario: {_id : usuario},
      marca: {_id: marca},
      estadoEquipo: {_id: estadoEquipo},
      tipoEquipo: {_id : tipoEquipo}
    }
    try {
      Swal.fire({
        allowOutsideClick: false,
        title: 'Cargando....',
        text: 'Por favor espere mientras se crea el nuevo activo',
        timer: 5000//milisegundos
      });
      Swal.showLoading();
      const {data} = await postInventarios(inventarioModel);
      console.log(data);
      Swal.close();
      handleOpenModal();
      listarInventarios();
    } catch (error) {
      console.log("error al crear el inventario");
      Swal.close();
    }
  }

  return (
    <div className='sidebar'>
      <div className='container-fluid'>
        <div className='row'>
          <div className='col'>
            <div className='xClose'>
              <h3>
                Nuevo Activo
              </h3>
              <i className="btn btn-danger fa-sharp fa-solid fa-xmark" onClick={handleOpenModal}></i>
            </div>
          </div>
        </div>
        <div className='row'>
          <div className='col'>
            <hr />
          </div>
        </div>
        <form
          className='form'
          onSubmit={(e)=>
            handleOnSubmit(e)
          }
        >
          <div className='row'>
            <div className='col'>
              <div className="mb-3">
                <label for="serialid" className="form-label">Serial</label>
                <input
                  required
                  value={serial}
                  onChange={(e) => {
                    handleOnChange(e);
                  }}
                  type="text"
                  name='serial'
                  className="form-control"
                  id="serialid" />
              </div>
            </div>
            <div className='col'>
              <div className="mb-3">
                <label for="modeloid" className="form-label">Modelo</label>
                <input
                  required
                  value={modelo}
                  onChange={(e) => {
                    handleOnChange(e);
                  }}
                  type="text"
                  name='modelo'
                  className="form-control"
                  id="modeloid" />
              </div>
            </div>
            <div className='col'>
              <div className="mb-3">
                <label for="descripcionid" className="form-label">Descripción</label>
                <input
                  required
                  value={descripcion}
                  onChange={(e) => {
                    handleOnChange(e);
                  }}
                  type="text"
                  name='descripcion'
                  className="form-control"
                  id="descripcionid" />
              </div>
            </div>
            <div className='col'>
              <div className="mb-3">
                <label for="colorid" className="form-label">Color</label>
                <input
                  required
                  value={color}
                  onChange={(e) => {
                    handleOnChange(e);
                  }}
                  type="text"
                  name='color'
                  className="form-control"
                  id="colorid" />
              </div>
            </div>
          </div>
          <div className='row'>
            <div className='col'>
              <div className="mb-3">
                <label for="fotoid" className="form-label">Foto</label>
                <input
                  required
                  value={foto}
                  onChange={(e) => {
                    handleOnChange(e);
                  }}
                  type="url"
                  name='foto'
                  className="form-control"
                  id="fotoid" />
              </div>
            </div>
            <div className='col'>
              <div className="mb-3">
                <label for="fechacompraid" className="form-label">Fecha Compra</label>
                <input
                  required
                  value={fechaCompra}
                  onChange={(e) => {
                    handleOnChange(e);
                  }}
                  type="date"
                  name='fechaCompra'
                  className="form-control"
                  id="fechacompraid" />
              </div>
            </div>
            <div className='col'>
              <div className="mb-3">
                <label for="precioid" className="form-label">Precio</label>
                <input
                  required
                  value={precio}
                  onChange={(e) => {
                    handleOnChange(e);
                  }}
                  type="number"
                  name='precio'
                  className="form-control"
                  id="precioid" />
              </div>
            </div>
            <div className='col'>
              <div className="mb-3">
                <label className="form-label">Usuario</label>
                <select
                  required
                  className="form-select"
                  onChange={(e) => handleOnChange(e)}
                  name='usuario'
                  value={usuario}
                  >
                  <option selected> --escoge un usuario--</option>
                  {usuarios.map ((usuario) => {
                      return (<option key={usuario._id} value={usuario._id}>{usuario.nombre}</option>)
                    })}
                </select>
              </div>
            </div>
          </div>
          <div className='row'>
            <div className='col'>
              <div className="mb-3">
                <label for="marcaid" className="form-label">Marca</label>
                <select
                  onChange={(e) => handleOnChange(e)}
                  required
                  className="form-select"
                  name='marca'
                  value={marca}>
                  <option selected> --escoge una marca--</option>
                  {marcas.map ((marca) => {
                      return (<option key={marca._id} value={marca._id}>{marca.nombre}</option>)
                    })}
                </select>
              </div>
            </div>
            <div className='col'>
              <div className="mb-3">
                <label for="tipoequipoid" className="form-label">Tipo Equipo</label>
                <select className="form-select"
                  required
                  onChange={(e) => handleOnChange(e)}
                  name='tipoEquipo'
                  value={tipoEquipo}>
                  <option selected> --escoge un tipo--</option>
                  {tipos.map ((tipo) => {
                      return (<option key={tipo._id} value={tipo._id}>{tipo.nombre}</option>)
                    })}
                </select>
              </div>
            </div>
            <div className='col'>
              <div className="mb-3">
                <label for="estadoequipoid" className="form-label">Estado Equipo</label>
                <select className="form-select"
                  required
                  onChange={(e) => handleOnChange(e)}
                  name='estadoEquipo'
                  value={estadoEquipo}>
                  <option selected> --escoge un estado--</option>
                  {estados.map ((estado) => {
                      return (<option key={estado._id} value={estado._id}>{estado.nombre}</option>)
                    })}
                </select>
              </div>
            </div>
            <div className='col'>
              <div className="mb-3">
              </div>
            </div>
          </div>
          <div className='row'>
            <div className='col'>
              <button type="onSubmit" className='btn btn-primary'>Guardar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  )
}
