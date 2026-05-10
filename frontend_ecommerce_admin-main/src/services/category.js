import { axiosInstance } from "../helpers/axios-config";

export const getCategories = () => {
    return axiosInstance.get('category', {
        headers:{
            'Content-type' : 'application/json'
        }
    });
}
export const postCategory = (data) => {
    return axiosInstance.post('category', data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const getCategoryById = (categoryId) => {
    return axiosInstance.get(`category/${categoryId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const putCategory = (categoryId, data) => {
    return axiosInstance.put(`category/edit/${categoryId}`,data,  {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const deleteCategoryById = (categoryId) => {
    return axiosInstance.delete(`category/delete/${categoryId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
