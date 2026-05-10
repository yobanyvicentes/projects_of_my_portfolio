import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom'
import { useParams } from 'react-router-dom';
import { getUsuarios } from '../../services/usuarioService';
import { getEstadoEquipos } from '../../services/estadoEquipoService';
import { getMarcas } from '../../services/marcaService';
import { getTipoEquipos } from '../../services/tipoEquipoService';
import { getInventariosById, putInventarios } from '../../services/inventarioService';
import Swal from 'sweetalert2';
import { Redirect } from 'react-router-dom/cjs/react-router-dom.min';

export const InventarioUpdate = () => {

    let {inventarioId = ''} = useParams();
    const [inventario, setInventario] = useState({});

    const [usuarios, setUsuarios] = useState([]);
    const [marcas, setMarcas] = useState([]);
    const [tipos, setTipos] = useState([]);
    const [estados, setEstados] = useState([]);

    const [valoresform, setValoresform] = useState({});
    const { serial = '', modelo = '', descripcion = '', color = '', foto = '', fechaCompra = '', precio = '', usuario,    marca, estadoEquipo, tipoEquipo } = valoresform;

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

    const getInventario = async () => {
        try {
            Swal.fire({
                allowOutsideClick: false,
                title: 'Cargando....',
                text: 'Por favor espere',
                timer: 3000//milisegundos
              });
              Swal.showLoading();
              Swal.close();
            let { data } = await getInventariosById(inventarioId);
            console.log(data);
            let { usuario, marca, estadoEquipo, tipoEquipo} = data;
            let idUser = usuario._id;
            let idMarca = marca._id;
            let idEstado = estadoEquipo._id;
            let idTipo = tipoEquipo._id;
            data.usuario = idUser;
            data.marca= idMarca;
            data.estadoEquipo = idEstado;
            data.tipoEquipo = idTipo;
            console.log(data);
            setInventario(data);
        } catch (error) {
            Swal.close();
            console.log(error);
        }
    }
    useEffect(() => {
        getInventario();
    }, [inventarioId]);

    const handleOnChange = ({ target }) => {
        const { name, value } = target;
        setValoresform({ ...valoresform, [name]: value });
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
              timer: 3000//milisegundos
            });
            Swal.showLoading();
            const {data} = await putInventarios(inventarioId, inventarioModel);
            console.log(data);
            Swal.close();
            Redirect(`/`);
          } catch (error) {
            console.log("error al actualizar el inventario");
            Swal.close();
            Swal.fire('Error', 'error al actualizar el inventario')
          }
    }

    useEffect(() => {
            setValoresform({
            serial: inventario.serial,
            modelo : inventario.modelo,
            descripcion : inventario.descripcion,
            color : inventario.color,
            foto : inventario.foto,
            fechaCompra: inventario.fechaCompra,
            precio: inventario.precio,
            usuario: inventario.usuario,
            marca: inventario.marca,
            tipoEquipo: inventario.tipoEquipo,
            estadoEquipo: inventario.estadoEquipo,
        });
    }, [inventario])

    return (
        <div className='container-fluid mt-3 mb-2'>
            <div className='card'>
                <div className='card-header'>
                    <h3>Detalle del Activo</h3>
                </div>
                <div className='card-body'>
                    <div className='row'>
                        <div className='col-md-3'>
                            <img src={inventario?.foto} alt="none"/>
                        </div>
                        <div className='col-md-9'>
                            <form
                                className='form'
                                onSubmit={(e) =>
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
                                                <option> --escoge un usuario--</option>
                                                {usuarios.map((usuario) => {
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
                                                required
                                                className="form-select"
                                                onChange={(e) => handleOnChange(e)}
                                                name='marca'
                                                value={marca}>
                                                <option selected> --escoge una marca--</option>
                                                {marcas.map((marca) => {
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
                                                {tipos.map((tipo) => {
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
                                                {estados.map((estado) => {
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
                                        <Link type='onSubmit' className='btn btn-primary' to={`../`}  onClick={handleOnSubmit}>
                                            Actualizar
                                        </Link>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}
