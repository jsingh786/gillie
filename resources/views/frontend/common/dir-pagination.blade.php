<div class="pagination">
<ul class="pagination" ng-if="1 < pages.length || !autoHide">
    <div class="pag_left">
        <p>Showing [[ range.lower ]] to [[ range.upper ]]  of [[ range.total ]] </p>
    </div>
    <div class="pag_right">

    <li ng-if="boundaryLinks" ng-class="{ disabled : pagination.current == 1 }">
        <a href="" ng-click="setCurrent(1)">&laquo;</a>
    </li>
    <li ng-if="directionLinks" ng-class="{ disabled : pagination.current == 1 }">
        <a href="" ng-click="setCurrent(pagination.current - 1)">&lsaquo;</a>
    </li>
    <li ng-repeat="pageNumber in pages track by tracker(pageNumber, $index)" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == '...' }">
        <a href="" ng-click="setCurrent(pageNumber)">[[ pageNumber ]]</a>
    </li>

    <li ng-if="directionLinks" ng-class="{ disabled : pagination.current == pagination.last }">
        <a href="" ng-click="setCurrent(pagination.current + 1)">&rsaquo;</a>
    </li>
    <li ng-if="boundaryLinks"  ng-class="{ disabled : pagination.current == pagination.last }">
        <a href="" ng-click="setCurrent(pagination.last)">&raquo;</a>
    </li>
        </div>
</ul>
</div>