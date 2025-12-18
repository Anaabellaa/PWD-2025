<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Bookmarks Controller
 *
 * @property \App\Model\Table\BookmarksTable $Bookmarks
 * @method \App\Model\Entity\Bookmark[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BookmarksController extends AppController
{
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $bookmarks = $this->paginate($this->Bookmarks);

        $this->set(compact('bookmarks'));
    }

    public function view($id = null)
    {
        $bookmark = $this->Bookmarks->get($id, [
            'contain' => ['Users', 'Tags'],
        ]);

        $this->set(compact('bookmark'));
    }

    public function add()
    {
        $bookmark = $this->Bookmarks->newEmptyEntity();
        if ($this->request->is('post')) {
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->getData());
            if ($this->Bookmarks->save($bookmark)) {
                $this->Flash->success(__('The bookmark has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bookmark could not be saved. Please, try again.'));
        }
        $users = $this->Bookmarks->Users->find('list', ['limit' => 200])->all();
        $tags = $this->Bookmarks->Tags->find('list', ['limit' => 200])->all();
        $this->set(compact('bookmark', 'users', 'tags'));
    }

    public function edit($id = null)
    {
        $bookmark = $this->Bookmarks->get($id, [
            'contain' => ['Tags'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->getData());
            if ($this->Bookmarks->save($bookmark)) {
                $this->Flash->success(__('The bookmark has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bookmark could not be saved. Please, try again.'));
        }
        $users = $this->Bookmarks->Users->find('list', ['limit' => 200])->all();
        $tags = $this->Bookmarks->Tags->find('list', ['limit' => 200])->all();
        $this->set(compact('bookmark', 'users', 'tags'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bookmark = $this->Bookmarks->get($id);
        if ($this->Bookmarks->delete($bookmark)) {
            $this->Flash->success(__('The bookmark has been deleted.'));
        } else {
            $this->Flash->error(__('The bookmark could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Tags method
     *
     * Muestra los marcadores asociados a etiquetas específicas.
     */
    public function tags()
    {
        // Extrae las etiquetas desde la URL
        $tags = $this->request->getParam('pass');

        // Busca los bookmarks etiquetados
        $bookmarks = $this->Bookmarks->find('tagged', [
            'tags' => $tags
        ]);

        // Envía a la vista
        $this->set([
            'bookmarks' => $bookmarks,
            'tags' => $tags
        ]);
    }
}