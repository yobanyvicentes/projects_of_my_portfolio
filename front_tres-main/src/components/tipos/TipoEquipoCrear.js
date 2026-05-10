import React, { useState } from 'react';
import { postTipoEquipos } from '../../services/tipoEquipoService';
import Swal from 'sweetalert2';

export const TipoEquipoCrear = ({ listarTipoEquipos }) => {

    const [valoresform, setValoresform] = useState({});
    const { nombre = '', estado = '' } = valoresform;

    const handleOnSubmit = async (e) => {
        e.preventDefault();
        const tipoEquipoModel = { nombre, estado };
        try {
            Swal.fire({
                allowOutsideClick: false, title: 'Cargando....', text: 'Por favor espere', timer: 2000//milisegundos
            });
            const { data } = await postTipoEquipos(tipoEquipoModel);
            console.log(data);
            listarTipoEquipos();
        } catch (error) {
            Swal.fire('Error', 'hubo un error', 'error')
            console.log("error al crear el tipoEquipo");
        }
    }

    const handleOnChange = ({ target }) => {
        const { name, value } = target;
        setValoresform({ ...valoresform, [name]: value });
    }

    return (
        <div className='row'>
            <div className='col'>
                <form
                    className='form'
                    onSubmit={(e) => {
                        handleOnSubmit(e)
                    }
                    }
                >
                    <div className='row' te>
                        <h5>Crear TipoEquipo</h5>
                    </div>
                    <div className='row'>
                        <div className='col-md-4'>
                            <div className='mb-3'>
                                <label className='form-label' for='nombreid'>Nombre</label>
                                <input className='form-control' type="text" name="nombre" value={nombre} id='nombreid' required
                                    onChange={(e) => {
                                        handleOnChange(e);
                                    }}
                                />
                            </div>
                        </div>
                        <div className='col-md-4'>
                            <div className='mb-3'>
                                <label className='form-label' for='estadoid'>Estado</label>
                                <select className='form-select' name="estado" value={estado} id='estadoid' required
                                    onChange={(e) => {
                                        handleOnChange(e);
                                    }}
                                >
                                    <option selected>--Seleccione--</option>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div className='row'>
                        <div className='col-md-1'>
                            <button className='btn btn-primary' type="onSubmit">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    )
}
