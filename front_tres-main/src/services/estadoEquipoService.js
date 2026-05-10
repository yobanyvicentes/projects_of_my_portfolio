import { axiosInstance } from "../helpers/axios-config";

export const getEstadoEquipos = () => {
    return axiosInstance.get('estado-equipo', {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const getEstadoEquipoById = (estadoEquipoId) => {
    return axiosInstance.get(`estado-equipo/${estadoEquipoId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const postEstadoEquipos = (data) => {
    return axiosInstance.post('estado-equipo', data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const putEstadoEquipos = (estadoEquipoId, data) => {
    return axiosInstance.put(`estado-equipo/${estadoEquipoId}`,data,  {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
