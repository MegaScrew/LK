import { createStore } from 'vuex'
import axios from 'axios'
import router from '../router'

export default createStore({
  state: {
    user: {
      Id: localStorage.getItem('userId'),
      Name: localStorage.getItem('userName'),
      LastName: localStorage.getItem('userLastName'),
      SecondName: localStorage.getItem('userSecondName'),
      Seriya: localStorage.getItem('userSeriya'),
      Nomer: localStorage.getItem('userNomer'),
      KemKogda: localStorage.getItem('userKemKogda'),
      IsActive: localStorage.getItem('userIsActive'),
      IdManager: localStorage.getItem('userIdManager')
    },
    manager: {
      Id: localStorage.getItem('managerId'),
      Name: localStorage.getItem('managerName'),
      LastName: localStorage.getItem('managerLastName'),
      SecondName: localStorage.getItem('managerSecondName')
    },
    company: [
      {
        Id: localStorage.getItem('companyId'),
        Title: localStorage.getItem('companyTitle'),
        Revenue: localStorage.getItem('companyRevenue'),
        PricePerKg: localStorage.getItem('companyPricePerKg'),
        PaymentFor: localStorage.getItem('companyPaymentFor'),
        OuterNumber: localStorage.getItem('companyOuterNumber'),
        InnerNumber: localStorage.getItem('companyInnerNumber'),
        Retailer: localStorage.getItem('companyRetailer'),
        Deal_id: localStorage.getItem('companyDeal_id'),
        Balance: localStorage.getItem('companyBalance'),
        Type: localStorage.getItem('companyType')
      }
    ],
    photo: [
    ],
    invoices: [
    ],
    attorney: [
    ],
    shipments1: [
    ],
    shipments2: [
    ],
    certificate: [
    ],
    contract: [
    ],
    currentObject: {
      CompanyId: localStorage.getItem('companyId'),
      Title: localStorage.getItem('companyTitle'),
      Revenue: localStorage.getItem('companyRevenue'),
      PricePerKg: localStorage.getItem('companyPricePerKg'),
      PaymentFor: localStorage.getItem('companyPaymentFor'),
      OuterNumber: localStorage.getItem('companyOuterNumber'),
      InnerNumber: localStorage.getItem('companyInnerNumber'),
      Retailer: localStorage.getItem('companyRetailer'),
      Deal_id: localStorage.getItem('companyDeal_id'),
      Balance: localStorage.getItem('companyBalance'),
      Type: localStorage.getItem('companyType')
    }
  },
  mutations: {
    SET_USER_DATA: (state, user) => {
      state.user.Id = user.ID
      state.user.Name = user.NAME
      state.user.LastName = user.LAST_NAME
      state.user.SecondName = user.SECOND_NAME
      state.user.Seriya = user.SERIYA
      state.user.Nomer = user.NOMER
      state.user.KemKogda = user.KEMKOGDA
      state.user.IsActive = user.ISACTIVE
      state.user.IdManager = user.ID_MANAGER
    },
    CLEAR_USER_DATA: (state) => {
      state.user.Id = 0
      state.user.Name = 0
      state.user.LastName = 0
      state.user.SecondName = 0
      state.user.Seriya = 0
      state.user.Nomer = 0
      state.user.KemKogda = 0
      state.user.IsActive = 0
      state.user.IdManager = 0
    },
    SET_MANAGER_DATA: (state, manager) => {
      state.manager.Id = manager.ID
      state.manager.Name = manager.NAME
      state.manager.LastName = manager.LAST_NAME
      state.manager.SecondName = manager.SECOND_NAME
    },
    CLEAR_MANAGER_DATA: (state) => {
      state.manager.Id = 0
      state.manager.Name = 0
      state.manager.LastName = 0
      state.manager.SecondName = 0
    },
    SET_COMPANY_DATE: (state, company) => {
      // console.log('SET_COMPANY_DATE')
      state.company.splice(0)
      for (let index = 0; index < company.length; index++) {
        state.company.push({ Id: company[index].COMPANY_ID, Title: company[index].IS_PRIMARY })
      }
    },
    UPDATE_COMPANY_DATA: (state, company2) => {
      for (let index = 0; index < state.company.length; index++) {
        for (let index2 = 0; index2 < company2.length; index2++) {
          if (state.company[index].Id === Number(company2[index2].ID)) {
            state.company[index].Title = company2[index2].TITLE
            state.company[index].Revenue = company2[index2].REVENUE
            state.company[index].PricePerKg = company2[index2].UF_CRM_1613731949
            state.company[index].PaymentFor = company2[index2].UF_CRM_1619173084
            state.company[index].OuterNumber = company2[index2].UF_CRM_1579359748326
            state.company[index].InnerNumber = company2[index2].UF_CRM_1594794891
            state.company[index].Retailer = company2[index2].UF_CRM_1580400783014
            state.company[index].Deal_id = company2[index2].DEAL_ID
            state.company[index].Balance = company2[index2].Balance
            state.company[index].Type = company2[index2].Type
          }
        }
      }
    },
    CLEAR_COMPANY_DATA: (state) => {
      state.company.splice(0)
    },
    SET_PHOTO_DATA: (state, photo) => {
      state.photo.id = 0
    },
    CLEAR_PHOTO_DATA: (state) => {
      state.photo.splice(0)
    },
    SET_INVOICES_DATA: (state, invoices) => {
      state.invoices.id = 0
    },
    CLEAR_INVOICE_DATA: (state) => {
      state.invoices.splice(0)
    },
    SET_LOCAL_STORAGE: (state) => {
      localStorage.setItem('userId', state.user.Id)
      localStorage.setItem('userName', state.user.Name)
      localStorage.setItem('userLastName', state.user.LastName)
      localStorage.setItem('userSecondName', state.user.SecondName)
      localStorage.setItem('userSeriya', state.user.Seriya)
      localStorage.setItem('userNomer', state.user.Nomer)
      localStorage.setItem('userKemKogda', state.user.KemKogda)
      localStorage.setItem('userIsActive', state.user.IsActive)
      localStorage.setItem('userIdManager', state.user.IdManager)
      localStorage.setItem('managerId', state.manager.Id)
      localStorage.setItem('managerName', state.manager.Name)
      localStorage.setItem('managerLastName', state.manager.LastName)
      localStorage.setItem('managerSecondName', state.manager.SecondName)
    },
    SET_CURRENT_OBJECT: (state, id) => {
      for (let index = 0; index < state.company.length; index++) {
        if (state.company[index].Id === id) {
          state.currentObject.CompanyId = id
          state.currentObject.Title = state.company[index].Title
          state.currentObject.Revenue = state.company[index].Revenue
          state.currentObject.PricePerKg = state.company[index].PricePerKg
          state.currentObject.PaymentFor = state.company[index].PaymentFor
          state.currentObject.OuterNumber = state.company[index].OuterNumber
          state.currentObject.InnerNumber = state.company[index].InnerNumber
          state.currentObject.Retailer = state.company[index].Retailer
          state.currentObject.Deal_id = state.company[index].Deal_id
          state.currentObject.Balance = state.company[index].Balance
          state.currentObject.Type = state.company[index].Type
          localStorage.setItem('companyId', id)
          localStorage.setItem('companyTitle', state.company[index].Title)
          localStorage.setItem('companyRevenue', state.company[index].Revenue)
          localStorage.setItem('companyPricePerKg', state.company[index].PricePerKg)
          localStorage.setItem('companyPaymentFor', state.company[index].PaymentFor)
          localStorage.setItem('companyOuterNumber', state.company[index].OuterNumber)
          localStorage.setItem('companyInnerNumber', state.company[index].InnerNumber)
          localStorage.setItem('companyRetailer', state.company[index].Retailer)
          localStorage.setItem('companyDeal_id', state.company[index].Deal_id)
          localStorage.setItem('companyBalance', state.company[index].Balance)
          localStorage.setItem('companyType', state.company[index].Type)
        }
      }
    },
    CLEAR_CURRENT_OBJECT: (state) => {
      state.currentObject.CompanyId = 0
      state.currentObject.Title = 0
      state.currentObject.Revenue = 0
      state.currentObject.PricePerKg = 0
      state.currentObject.PaymentFor = 0
      state.currentObject.OuterNumber = 0
      state.currentObject.InnerNumber = 0
      state.currentObject.Retailer = 0
      state.currentObject.Deal_id = 0
      state.currentObject.Balance = 0
      state.currentObject.Type = 0
    },
    SET_ATTORNEY: (state, attorney) => {
      state.attorney.splice(0)
      for (let index = 0; index < attorney.length; index++) {
        state.attorney.push({ Id: attorney[index].id, pdfUrl: attorney[index].pdfUrlMachine, Title: attorney[index].title, imageUrl: attorney[index].imageUrlMachine })
      }
    },
    SET_SHIPMENTS1: (state, shipments) => {
      state.shipments1.splice(0)
      for (let index = 0; index < shipments.length; index++) {
        state.shipments1.push({ Id: shipments[index].id, Date: shipments[index].date, Nomenclature: shipments[index].name_of_numenclature, Weight: shipments[index].shipped_with_adjustments })
      }
    },
    SET_SHIPMENTS2: (state, shipments) => {
      state.shipments2.splice(0)
      for (let index = 0; index < shipments.length; index++) {
        state.shipments2.push({ Id: shipments[index].id, Date: shipments[index].date, Nomenclature: shipments[index].name_of_numenclature, Weight: shipments[index].shipped_with_adjustments })
      }
    },
    CLEAR_ATTORNEY: (state) => {
      state.attorney.splice(0)
    },
    CLEAR_SHIPMENTS: (state) => {
      state.shipments1.splice(0)
      state.shipments2.splice(0)
    },
    SET_CERTIFICATE: (state, certificate) => {
      state.certificate.splice(0)
      for (let index = 0; index < certificate.length; index++) {
        state.certificate.push({ url: certificate[index] })
      }
    },
    CLEAR_CERTIFICATE: (state) => {
      state.certificate.splice(0)
    },
    SET_CONTRACT: (state, contract) => {
      state.contract.splice(0)
      for (let index = 0; index < contract.length; index++) {
        state.contract.push({ url: contract[index] })
      }
    },
    CLEAR_CONTRACT: (state) => {
      state.contract.splice(0)
    }
  },
  actions: {
    async LOGIN ({ dispatch, commit }, formData) {
      if (localStorage.getItem('userIsActive')) {
        // console.log('Yes')
      } else {
        // console.log('No')
        await axios.get('https://is.smaip.ru/testvue/b24/handler.php', { params: { formData } })
          .then((response) => {
            // console.log(response.data)
            commit('SET_USER_DATA', response.data.user)
            if (response.data.user.ISACTIVE) {
              commit('SET_MANAGER_DATA', response.data.manager)
              commit('SET_COMPANY_DATE', response.data.company)
              dispatch('GET_COMPANY', this.getters.COMPANY)
              commit('SET_LOCAL_STORAGE')
              router.push('/')
            }
            // console.log(response.data)
          })
          .catch((error) => {
            console.log(error)
          })
      }
    },
    async GET_COMPANY ({ commit }, formData) {
      const temp = []
      for (let index = 0; index < formData.length; index++) {
        temp.push(formData[index].Id)
      }
      const temp2 = {
        Steps: '2',
        data: temp
      }
      formData = temp2
      await axios.get('https://is.smaip.ru/testvue/b24/handler.php', { params: { formData } })
        .then((response) => {
          commit('UPDATE_COMPANY_DATA', response.data)
          // console.log(response.data)
        })
        .catch((error) => {
          console.log(error)
        })
    },
    async GET_COMPANY_USER ({ commit }, formData) {
      const temp = {
        Steps: '3',
        data: formData.Id
      }
      formData = temp
      await axios.get('https://is.smaip.ru/testvue/b24/handler.php', { params: { formData } })
        .then((response) => {
          commit('SET_COMPANY_DATE', response.data.company)
          commit('UPDATE_COMPANY_DATA', response.data.company_detail)
          // console.log(response.data)
        })
        .catch((error) => {
          console.log(error)
        })
    },
    async GET_ATTORNEY ({ commit }, formData) {
      // console.log(formData)
      const temp = {
        Steps: '4',
        data: formData
      }
      formData = temp
      await axios.get('https://is.smaip.ru/testvue/b24/handler.php', { params: { formData } })
        .then((response) => {
          commit('SET_ATTORNEY', response.data.attorney)
          // console.log(response.data)
        })
        .catch((error) => {
          console.log(error)
        })
    },
    async GET_SHIPMENTS ({ commit }, formData) {
      // console.log(formData)
      const temp = {
        Steps: '5',
        data: formData
      }
      formData = temp
      await axios.get('https://is.smaip.ru/testvue/b24/handler.php', { params: { formData } })
        .then((response) => {
          commit('SET_SHIPMENTS1', response.data.shipments1)
          commit('SET_SHIPMENTS2', response.data.shipments2)
          // console.log(response.data)
        })
        .catch((error) => {
          console.log(error)
        })
    },
    async GET_CERTIFICATE ({ commit }, formData) {
      // console.log(formData)
      const temp = {
        Steps: '6',
        data: formData
      }
      formData = temp
      await axios.get('https://is.smaip.ru/testvue/b24/handler.php', { params: { formData } })
        .then((response) => {
          commit('SET_CERTIFICATE', response.data.certificate)
          // console.log(response.data)
          return true
        })
        .catch((error) => {
          console.log(error)
          return false
        })
    },
    async SET_CERTIFICATE ({ dispatch, commit }, formData) {
      const config = { headers: { 'Content-Type': 'multipart/form-data' } }
      await axios.post('https://is.smaip.ru/testvue/b24/handler.php', formData, config)
        .then((response) => {
          if (response.data.message === 0) {
            dispatch('GET_CERTIFICATE', response.data.dealId)
          }
        })
        .catch((error) => {
          console.log(error)
        })
    },
    async GET_CONTRACT ({ commit }, formData) {
      // console.log(formData)
      const temp = {
        Steps: '8',
        data: formData
      }
      formData = temp
      await axios.get('https://is.smaip.ru/testvue/b24/handler.php', { params: { formData } })
        .then((response) => {
          commit('SET_CONTRACT', response.data.contract)
          // console.log(response.data)
          return true
        })
        .catch((error) => {
          console.log(error)
          return false
        })
    },
    async SET_CONTRACT ({ dispatch, commit }, formData) {
      const config = { headers: { 'Content-Type': 'multipart/form-data' } }
      await axios.post('https://is.smaip.ru/testvue/b24/handler.php', formData, config)
        .then((response) => {
          if (response.data.message === 0) {
            dispatch('GET_CONTRACT', response.data.dealId)
          }
        })
        .catch((error) => {
          console.log(error)
        })
    },
    LOGOUT ({ commit }) {
      // console.log('Exit')
      commit('CLEAR_USER_DATA')
      commit('CLEAR_MANAGER_DATA')
      commit('CLEAR_COMPANY_DATA')
      commit('CLEAR_CURRENT_OBJECT')
      localStorage.clear()
    },
    SET_CURRENTOBJECT ({ commit }, id) {
      commit('SET_CURRENT_OBJECT', id)
    },
    CLEAR_ATTORNEY ({ commit }) {
      commit('CLEAR_ATTORNEY')
    },
    CLEAR_SHIPMENTS ({ commit }) {
      commit('CLEAR_SHIPMENTS')
    },
    CLEAR_CERTIFICATE ({ commit }) {
      commit('CLEAR_CERTIFICATE')
    }
  },
  getters: {
    USER (state) {
      return state.user
    },
    IS_ACTIVE (state) {
      return state.user.IsActive
    },
    MANAGER (state) {
      return state.manager
    },
    COMPANY (state) {
      return state.company
    },
    PHOTO (state) {
      return state.photo
    },
    INVOICES (state) {
      return state.invoices
    },
    CURRENT_OBJECT (state) {
      return state.currentObject
    },
    ATTORNEY (state) {
      return state.attorney
    },
    SHIPMENTS1 (state) {
      return state.shipments1
    },
    SHIPMENTS2 (state) {
      return state.shipments2
    },
    CERTIFICATE (state) {
      return state.certificate
    },
    CONTRACT (state) {
      return state.contract
    }
  },
  modules: {
  }
})
