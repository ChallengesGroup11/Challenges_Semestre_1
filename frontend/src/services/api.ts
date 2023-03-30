/* eslint-disable quote-props */
import axios from 'axios'
export namespace ApiService {
  const api = axios.create({
    baseURL: import.meta.env.VITE_CHALLENGE_URL,
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
      'Content-Type': 'application/json',
    },
  })

  const apiPatch = axios.create({
    baseURL: import.meta.env.VITE_CHALLENGE_URL,
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
      'Content-Type': 'application/merge-patch+json',
    },
  })

  export const fetchbById = async (url: string, id: string) => {
    const response = await api.get(`${url}/${id}`)
    return response.data
  }

  export const fetchAll = async (url: string) => {
    const response = await api.get(url)
    return response.data['hydra:member']
  }

  export const insert = async (url: string, data: any) => {
    const response = await api.post(url, data)
    return response.data
  }

  export const patch = async (url: string, data: any) => {
    const response = await apiPatch.patch(`${url}/${data.id}`, data)
    return response.data
  }

  export const patchDecrementCountCredit = async (idStudent: number, data: any) => {
    const response = await apiPatch.patch(`/students/${idStudent}/decrementCountCredit`, data)
    return response.data
  }



  export const deleteById = async (url: string, id: number) => {
    const response = await api.delete(`${url}/${id}`)
    return response.data
  }
}

// update non consitenct naming
export enum API_URL {
  BOOKINGS = '/bookings',
  DIRECTORS = '/directors',
  DRIVING_SHCOOLS = '/driving_schools',
  MONITORS = '/monitors',
  PACKAGES = '/packages',
  PAYMENTS = '/payments',
  STUDENTS = '/students',
}
