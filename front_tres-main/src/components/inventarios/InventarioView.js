import React, { useState, useEffect } from 'react';
import { getInventarios } from '../../services/inventarioService';
import { InventarioCrear } from './InventarioCrear';
import {InventarioCard} from './InventarioCard';
import Swal from 'sweetalert2';

export const InventarioView = () => {

  const [inventarios, setInventarios] = useState([]);
  const [openModal, setOpenModal] = useState(false);

  const listarInventarios = async () => {
    try {
      Swal.fire({
        allowOutsideClick: false,
        title: 'Cargando....',
        text: 'Por favor espere mientras se crea el nuevo activo',
        timer: 1000//milisegundos
      });
      Swal.showLoading();
      const { data } = await getInventarios();
      setInventarios(data);
      Swal.close();
    } catch (error) {
      console.log('ocurrió un error')
      Swal.close();
    }
  };

  useEffect(() => {
    listarInventarios();
  }, []);

  const handleOpenModal = () => {
    setOpenModal(!openModal);
  };

  return (
    <div className="container">
      <div className="cards mt-2 mb-2 row row-cols-1 row-cols-md-4 g-4">
        {
          inventarios.map((inventario) => {
            return <InventarioCard key={inventario._id} inventario={inventario}/>
          })
        }
      </div>
        {
        openModal ? <InventarioCrear handleOpenModal={handleOpenModal} listarInventarios={listarInventarios}/> :
        <button className="btn btn-primary btnplus" onClick={handleOpenModal}>
          <i className="fa-solid fa-plus"></i>
        </button>
        }
    </div>
  )
}
