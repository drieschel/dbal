<?php

namespace Doctrine\ORM\Persisters;

use Doctrine\ORM\PersistentCollection;

/**
 * Persister for one-to-many collections.
 */
class OneToManyPersister extends AbstractCollectionPersister
{
    /**
     * {@inheritdoc}
     *
     * @param <type> $coll
     * @return <type>
     * @override
     */
    protected function _getDeleteRowSql(PersistentCollection $coll)
    {
        $mapping = $coll->getMapping();
        $targetClass = $this->_em->getClassMetadata($mapping->getTargetEntityName());
        $table = $targetClass->getTableName();

        $ownerMapping = $targetClass->getAssociationMapping($mapping->getMappedByFieldName());

        $setClause = '';
        foreach ($ownerMapping->getSourceToTargetKeyColumns() as $sourceCol => $targetCol) {
            if ($setClause != '') $setClause .= ', ';
            $setClause .= "$sourceCol = NULL";
        }

        $whereClause = '';
        foreach ($targetClass->getIdentifierColumnNames() as $idColumn) {
            if ($whereClause != '') $whereClause .= ' AND ';
            $whereClause .= "$idColumn = ?";
        }

        return "UPDATE $table SET $setClause WHERE $whereClause";
    }

    /**
     * {@inheritdoc}
     *
     * @param <type> $element
     * @return <type>
     * @override
     */
    protected function _getDeleteRowSqlParameters(PersistentCollection $coll, $element)
    {
        return $this->_uow->getEntityIdentifier($element);
    }

    protected function _getInsertRowSql()
    {
        return "UPDATE xxx SET foreign_key = yyy WHERE foreign_key = zzz";
    }

    /* Not used for OneToManyPersister */
    protected function _getUpdateRowSql()
    {
        return;
    }
    
}
