                    <?php foreach($post_list as $post): ?>
                    <li>
                        <table>
                            <tr>
                                <td><a href="profile.php?id=<?= $post['Creatore']; ?>)"></a><?= $post["Creatore"]; ?></td>
                                <td><?= $post["DataPost"]; ?></td>
                                <?php if ($post["Creatore"] == $_COOKIE['NomeUtente']): ?>
                                    <td><button onclick="removePost(<?= $post['NrPost']; ?>)" class="access_required">Rimuovi</button></td>
                                <?php endif; ?>
                            </tr>
                            <tr><td><?= $post["TestoPost"]; ?></td></tr>
                            <tr><td><img src="<?= $post['ImmaginePost']; ?>" alt=""/></td></tr>
                            <tr>
                                <td><?= $post["LikePost"]; ?></td>
                                <td><button name="<?= $post['NrPost']; ?>_like_button" class="preference_button" onclick="<?php if(isset($_COOKIE['NomeUtente'])): ?>like(<?= $post['NrPost']; ?>)<?php endif; ?>">Like</button></td>
                                <td><?= $post["DislikePost"]; ?></td>
                                <td><button name="<?= $post['NrPost']; ?>_dislike_button" class="preference_button" onclick="<?php if(isset($_COOKIE['NomeUtente'])): ?>dislike(<?= $post['NrPost']; ?>)<?php endif; ?>">Dislike</button></td>
                                <?php if(isset($_COOKIE['NomeUtente'])): ?>
                                    <td><button id="add_comment_button" class="access_required" onclick="mostraFormCommenti(<?= $post['NrPost']; ?>, <?= $post['Creatore']; ?>, <?= $post['DataPost']; ?>, '')">Commenta</button></td>
                                <?php endif; ?>
                            </tr>
                            <tr><td><button onclick="mostraCommentiPost(<?= $post['NrPost']; ?>)">Mostra commenti</button></td></tr>
                        </table>
                        <ul id="<?= $post['NrPost']; ?>_comment_list">
                            <?php 
                                $stmt = $dbh->connect()->prepare("SELECT COMMENTI.*, COUNT(CASE WHEN INTERAZIONI.Tipo THEN 1 END) AS LikeCommento, COUNT(CASE WHEN NOT INTERAZIONE.Tipo THEN 1 END)
                                AS DislikeCommento FROM COMMENTI LEFT JOIN INTERAZIONI ON COMMENTI.NrCommento = INTERAZIONI.ElementId WHERE COMMENTI.NrPost = ? ORDER BY COMMENTI.DataCommento DESC");
                                if(!$stmt->execute(array($post['NrPost']))) {
                                    $stmt = null;
                                    header('location: ../../login.html?error=stmtfailed');
                                    exit();
                                }
                                $commenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach($commenti as $commento):
                            ?>
                                <li>
                                    <table>
                                        <tr>
                                            <td><a href="profile.php?id=<?= $commento['Creatore']; ?>)"><?= $commento["Creatore"]; ?></a></td>
                                            <td><?= $commento["DataCommento"]; ?></td>
                                            <?php if($commento['Creatore'] == $_COOKIE['NomeUtente']): ?>
                                                <td><button onclick="removeComment(<?= $commento['NrCommento']; ?>)" class="access_required">Rimuovi</button></td>
                                            <?php endif; ?>
                                        </tr>
                                        <tr><td><?= $commento["TestoCommento"]; ?></td></tr>
                                        <tr><td><img src="<?= $commento['ImmagineCommento']; ?>" alt=""/></td></tr>
                                        <tr>
                                            <td><?= $commento["LikeCommento"]; ?></td>
                                            <td><button id="<?= $commento['NrCommento']; ?>_like_button" class="preference_button"
                                            onclick="<?php if(isset($_COOKIE['NomeUtente'])): ?>like(<?= $commento['NrCommento']; ?>)<?php endif; ?>">Like</button></td>
                                            <td><?= $commento["DislikeCommento"]; ?></td>
                                            <td><button id="<?= $commento['NrCommento']; ?>_dislike_button" class="preference_button"
                                            onclick="<?php if(isset($_COOKIE['NomeUtente'])): ?>dislike(<?= $commento['NrCommento']; ?>)<?php endif; ?>">Dislike</button></td>
                                            <?php if(isset($_COOKIE['NomeUtente'])): ?>
                                                <td><button onclick="mostraFormCommenti($post['NrPost'], $post['Creatore'], $post['DataPost'], '@' + $commento['Creatore'])">Rispondi</button></td>
                                            <?php endif; ?>
                                        </tr>
                                    </table>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if(isset($_COOKIE['NomeUtente'])): ?>
                            <form action="addComment.php" method="post" name="add_comment_form">
                                <table>
                                    <tr>
                                        <td id="comment_post_info"></td>
                                        <td><input type="hidden" name="post_id_input" id="post_id_input"/></td>
                                    </tr>
                                    <tr>
                                        <td><label for="comment_img">Inserisci immagine (opzionale)</label></td>
                                        <td><input type="image" name="comment_img" id="comment_img" alt=""/></td>
                                    </tr>
                                    <tr>
                                        <td><label for="comment_text">Inserisci testo</label></td>
                                        <td><textarea name="comment_text" id="comment_text" required></textarea></td>
                                    </tr>
                                    <tr>
                                        <td><input type="reset" value="Annulla"/></td>
                                        <td><input type="submit" value="Scrivi"/></td>
                                    </tr>
                                </table>
                            </form>
                        <?php endif; ?>
                        </li>
                    <?php endforeach; ?>