<template>
  <v-card flat>
    <v-card-title class="d-flex justify-space-between align-center">
      <span class="text-h6">Loans</span>            
        <v-btn size="small" variant="tonal" :loading="loading" @click="loadLoans">
          Refresh
        </v-btn>      
    </v-card-title>

    <v-data-table
      class="elevation-1"
      :headers="headers"
      :items-per-page-options="[25, 50, 100]"
      :items-per-page="25"
      :items="loans"
      :loading="loading"
    >
      <template #item.loaned_at="{item}">
        {{ moment(item.loaned_at).format('MMM Do YYYY \\a\\t h:mm A') }}
      </template>

      <template #item.returned_at="{item}">
        {{ item.returned_at ? moment(item.returned_at).format('MMM Do YYYY \\a\\t h:mm A') : '-' }} <br />
        {{ moment(item.due_at).isAfter(moment()) ? 'OVERDUE' : 'ONTIME' }}
      </template>     

      <template #loading>
        <v-sheet class="pa-4 text-center">Loading loans...</v-sheet>
      </template>

      <template #item.actions="{ item }">
        <div class="d-flex flex-nowrap">
          <v-btn
            size="small"
            variant="text"
            icon="mdi-file-document-edit-outline"
            :disabled="loading"
            @click="extendLoan(item.id)"
          />          
        </div>
      </template>

    </v-data-table>

    <v-dialog persistent v-model="dialog.open" max-width="640">
      <v-card>
        <v-card-title class="text-h6">Extend Loan</v-card-title>

        <v-card-text>
          <v-form ref="authorForm" @submit.prevent="submitDialog">
            <v-row dense>
              <v-col cols="12" sm="6">
                <v-text-field
                  label="Additional days"
                  v-model.trim="dialog.form.additional_days"
                  autocomplete="off"
                />
              </v-col>                         
            </v-row>

            <button type="submit" class="d-none" />
          </v-form>
        </v-card-text>

        <v-card-actions class="justify-end">
          <v-btn variant="text" @click="closeDialog" :disabled="dialog.saving">Cancel</v-btn>
          <v-btn color="primary" :loading="dialog.saving" @click="submitDialog">
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-card>
</template>

<script>
import { toast } from 'vue3-toastify';
import axios from 'axios';
import moment from 'moment';

export default {
  name: 'LoansTab',

  data () {
    return {
      moment,

      loading: false,
      loans: [],
      headers: [
        { title: 'ID', key: 'id' },
        { title: 'User', key: 'user.name' },
        { title: 'Book', key: 'book.title' },
        { title: 'Loan Date', key: 'loaned_at' },
        { title: 'Return Date', key: 'returned_at' },
        { title: '', key: 'actions', sortable: false, align: 'end' },        
      ],
      dialog: {
        open: false,
        saving: false,
        form: {
          additional_days : '',
          id: ''         
        },
      }
    };
  },

  methods: {
    loadLoans () {
      this.loading = true;

      return axios.get('/api/v1/loans')
        .then(r => this.loans = r.data)
        .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', {type: 'error'});
          console.error(e);
        })
        .finally(() => this.loading = false);
    },
    dialogInit (id) {
      this.dialog.form = {
          additional_days : 0,
          id         
        };     
    },

    submitDialog () {
      if (this.dialog.saving) return;
      this.dialog.saving = true;

      const payload = {
        additional_days: this.dialog.form.additional_days.trim()        
      };
      
      const url = `/api/v1/loans/extend/${this.dialog.form.id}`;

      axios.put(url, payload)
        .then(() => {
          toast('Loand extended', { type: 'success' });
          this.loadLoans();
          this.closeDialog();
        })
        .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', { type: 'error' });
          console.error(e);
        })
        .finally(() => this.dialog.saving = false);
    },

    closeDialog () {
      this.dialog.open = false;
      this.dialogInit();
    },

    extendLoan (id) {
      this.dialogInit(id);            
      this.dialog.open = true;
    }
  },
  /*computed: {
    overdue: (due_at) => {
      return due_at > moment(due_at).isAfter(moment())
    }
  },*/
  mounted () {
    this.loadLoans();
  },
};
</script>
