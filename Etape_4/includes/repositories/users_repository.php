<?php

function userFindByCredentials($email, $password)
{
    $sql = '
        SELECT id, email, name, role, lang, notif
        FROM users
        WHERE email = :email AND password = :password
        LIMIT 1
    ';

    $stmt = getPDO()->prepare($sql);
    $stmt->bindValue(':email', (string) $email, PDO::PARAM_STR);
    $stmt->bindValue(':password', (string) $password, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch();

    return $user ?: null;
}

function userFindById($userId)
{
    $stmt = getPDO()->prepare('SELECT id, email, name, role, lang, notif FROM users WHERE id = :id LIMIT 1');
    $stmt->bindValue(':id', (int) $userId, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch();

    return $user ?: null;
}

function userExistsByEmail($email)
{
    $stmt = getPDO()->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
    $stmt->bindValue(':email', (string) $email, PDO::PARAM_STR);
    $stmt->execute();

    return (bool) $stmt->fetchColumn();
}

function userExistsByEmailExceptId($email, $exceptUserId)
{
    $stmt = getPDO()->prepare('SELECT id FROM users WHERE email = :email AND id <> :user_id LIMIT 1');
    $stmt->bindValue(':email', (string) $email, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', (int) $exceptUserId, PDO::PARAM_INT);
    $stmt->execute();

    return (bool) $stmt->fetchColumn();
}

function userCreate($name, $email, $password, $role = 'collaborateur')
{
    $sql = '
        INSERT INTO users (name, email, password, role, lang, notif)
        VALUES (:name, :email, :password, :role, :lang, :notif)
    ';

    $stmt = getPDO()->prepare($sql);
    $stmt->bindValue(':name', (string) $name, PDO::PARAM_STR);
    $stmt->bindValue(':email', (string) $email, PDO::PARAM_STR);
    $stmt->bindValue(':password', (string) $password, PDO::PARAM_STR);
    $stmt->bindValue(':role', (string) $role, PDO::PARAM_STR);
    $stmt->bindValue(':lang', 'fr', PDO::PARAM_STR);
    $stmt->bindValue(':notif', 'oui', PDO::PARAM_STR);
    $stmt->execute();

    return (int) getPDO()->lastInsertId();
}

function userUpdateProfile($userId, $name, $email)
{
    $stmt = getPDO()->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id');
    $stmt->bindValue(':id', (int) $userId, PDO::PARAM_INT);
    $stmt->bindValue(':name', (string) $name, PDO::PARAM_STR);
    $stmt->bindValue(':email', (string) $email, PDO::PARAM_STR);

    return $stmt->execute();
}

function userUpdateSettings($userId, $lang, $notif)
{
    $stmt = getPDO()->prepare('UPDATE users SET lang = :lang, notif = :notif WHERE id = :id');
    $stmt->bindValue(':id', (int) $userId, PDO::PARAM_INT);
    $stmt->bindValue(':lang', (string) $lang, PDO::PARAM_STR);
    $stmt->bindValue(':notif', (string) $notif, PDO::PARAM_STR);

    return $stmt->execute();
}
