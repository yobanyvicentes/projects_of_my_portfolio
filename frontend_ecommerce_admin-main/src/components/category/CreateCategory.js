import React, { useState } from 'react';
import { postCategory } from '../../services/category';
import Swal from 'sweetalert2';

export const CreateCategory = ({ listCategories }) => {

    const [formValues, setFormValues] = useState({});
    const { name = '', internal_id = '' } = formValues;

    const handleOnSubmit = async (e) => {
        e.preventDefault();
        const categoryModel = { name, internal_id };
        try {
            Swal.fire({
                allowOutsideClick: false, title: 'Cargando', text: 'Por favor espere'
            });
            const { data } = await postCategory(categoryModel);
            console.log(data);
            listCategories();
            setFormValues('');
            Swal.close();
        } catch (error) {
            Swal.fire('Error', 'hubo un error al crear el categoria', 'error')
            console.log("error al crear el categoria");
        }
    }

    const handleOnChange = ({ target }) => {
        const { name, value } = target;
        setFormValues({ ...formValues, [name]: value });
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
                        <h5>Crear Categoria</h5>
                    </div>
                    <div className='row'>
                        <div className='col-md-4'>
                            <div className='mb-3'>
                                <label className='form-label' for='nameId'>Nombre</label>
                                <input className='form-control' type="text" name="name" value={name} id='nameId' required
                                    onChange={(e) => {
                                        handleOnChange(e);
                                    }}
                                />
                            </div>
                        </div>
                        <div className='col-md-4'>
                            <div className='mb-3'>
                                <label className='form-label' for='internalId'>ID interno</label>
                                <input className='form-control' name="internal_id" value={internal_id} id='internalId' required
                                    onChange={(e) => {
                                        handleOnChange(e);
                                    }}
                               />
                            </div>
                        </div>
                        <div className='col-md-4'>
                            <div className='mb-3 mt-2'>
                                <button className='btn btn-primary mt-4' type="onSubmit" >Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    )
}
