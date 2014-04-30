<?php
namespace Mandala\BookModule;

use Doctrine\ORM\EntityRepository;
use Mandala\UserModule\User;

class BookFavoriteRepository extends EntityRepository
{
    public function add(User $user, Book $book)
    {
        $favorite = $this->findFavorite($user, $book);
        if ($favorite == null) {
            $favorite = new BookFavorite();
            $favorite->user = $user;
            $favorite->book = $book;
            $this->_em->persist($favorite);

            $book->favoritedCount++;
            $this->_em->persist($book);
            $this->_em->flush();
        }
    }

    public function remove(User $user, Book $book)
    {
        $favorite = $this->findFavorite($user, $book);
        if ($favorite !== null) {
            $this->_em->remove($favorite);

            $book->favoritedCount--;
            $this->_em->persist($book);
            $this->_em->flush();
        }
    }

    public function isFavorite(User $user, Book $book)
    {
        $favorite = $this->findFavorite($user, $book);

        return $favorite !== null;
    }

    private function findFavorite(User $user, Book $book)
    {
        $criteria = array(
            'user' => $user,
            'book' => $book
        );

        return $this->findOneBy($criteria);
    }
}