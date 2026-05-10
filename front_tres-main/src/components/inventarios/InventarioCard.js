import React from 'react'
import { Link } from 'react-router-dom'

export const InventarioCard = (props)  => {
  const {inventario} = props;
  return (
    <div className="col card-container" key={inventario._id}>
      <div className="card inventario">
        <img src={inventario.foto} className="card-img-top" alt={inventario.foto} />
        <div className="card-body">
          <h5 className="card-title"> {`${inventario.descripcion}`} </h5>
          <hr />
          <p className="card-text">{`Serial: ${inventario.serial}`}</p>
          <p className="card-text">{`Marca: ${inventario.marca.nombre}`}</p>
          <p className="card-text">{`Precio: ${inventario.precio}`}</p>
          <p className="card-text">
            <Link to={`inventario/edit/${inventario._id}`}> Ver más... </Link>
          </p>
        </div>
      </div>
    </div>
  )
}

