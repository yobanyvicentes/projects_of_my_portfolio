import React, { useState, useEffect } from 'react'
import { MarcaCrear } from './MarcaCrear';
import { getMarcas } from '../../services/marcaService';
import Swal from 'sweetalert2';
import { Link } from 'react-router-dom';

export const MarcaView = () => {

  const [marcas, setMarcas] = useState([]);

  const listarMarcas = async () => {
    try {
      Swal.fire({
        allowOutsideClick: false,
        title: 'Cargando....',
        text: 'Por favor espere',
        timer: 1000//milisegundos
      });
      Swal.showLoading();
      const { data } = await getMarcas();
      setMarcas(data);
      Swal.close();
    } catch (error) {
      console.log('ocurrió un error')
      Swal.close();
    }
  };

  useEffect(() => {
    listarMarcas();
  }, []);

  return (
    <div className='container-fluid'>
      <div className='card mt-3 mb-2'>
        <div className='card-header'>
          <h5>Marcas</h5>
        </div>
        <div className='card-body'>
          <MarcaCrear listarMarcas={listarMarcas} />
          <div className='row mt-5'>
            <div className='col'>
              <table className="table">
                <thead>
                  <tr>
                    <th className="col-md-2">#</th>
                    <th className="col-md-2">Nombre</th>
                    <th className="col-md-1">Estado</th>
                    <th className="col-md-3">Fecha Creación</th>
                    <th className="col-md-3">Fecha Actualización</th>
                    <th className="col-md-1">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  {
                    marcas.map((marca) => {
                      return (
                        <tr key={marca._id}>
                          <th className="col-md-2" value='index'>{1 + marcas.indexOf(marca)} </th>
                          <td className="col-md-2">{marca.nombre}</td>
                          <td className="col-md-1">{marca.estado}</td>
                          <td className="col-md-3">{marca.fechaCreacion}</td>
                          <td className="col-md-3">{marca.fechaActualizacion}</td>
                          <td className="col-md-1">
                            <Link to={`marca/edit/${marca._id}`}>
                              <button className='btn btn-success'>
                                editar
                              </button>
                            </Link>
                          </td>
                        </tr>
                      )
                    })
                  }
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

